<?php

//Importation de request et response
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => []
    ];

    private mixed $currentMiddleware = null;

    //Enregistre une route GET

    public function get(string $uri, array $handler): void 
    {
        $this->addRoute('GET', $uri, $handler);
    }
    
    public function post(string $uri, array $handler): void
    {
        $this->addRoute('POST', $uri, $handler);
    }
    
    public function put(string $uri, array $handler): void
    {
        $this->addRoute('PUT', $uri, $handler);
    }

    public function patch(string $uri, array $handler): void
    {
        $this->addRoute('PATCH', $uri, $handler);
    }
    
    public function delete(string $uri, array $handler): void
    {
        $this->addRoute('DELETE', $uri, $handler);
    }

    public function group(callable $middleware, callable $callback): void
    {
        $this->currentMiddleware = $middleware;
        $callback();
        $this->currentMiddleware = null;
    }

    private function addRoute(string $method, string $uri, array $handler): void
    {
        $uriRegex = $this->uriToRegex($uri);
        $this->routes[$method][] = [
            'pattern'    => $uriRegex['pattern'],
            'params'     => $uriRegex['params'],
            'handler'    => $handler,
            'middleware' => $this->currentMiddleware,
        ];
    }

    /**
     * Transforme un URI avec placeholders en regex
     * "/api/projets/{id}" → ['pattern' => '#^/api/projets/([^/]+)$#', 'params' => ['id']]
     */
    private function uriToRegex(string $uri): array
    {
        preg_match_all('/\{(\w+)\}/', $uri, $matches);
        $paramNames = $matches[1];
        
        $pattern = preg_replace('/\{\w+\}/', '([^/]+)', $uri);
        $pattern = '#^' . $pattern . '$#';
        
        return ['pattern' => $pattern, 'params' => $paramNames];
    }
    
    /**
     * Trouve la route correspondante et exécute le contrôleur
     */
    public function dispatch(Request $request): void
    {
        $method = $request->getMethod();
        $uri    = $request->getUri();
        
        // Vérifier que la méthode HTTP est gérée
        if (!isset($this->routes[$method])) {
            Response::error("Méthode HTTP non supportée", 405);
            return;
        }
        
        // Parcourir toutes les routes de cette méthode
        foreach ($this->routes[$method] as $route) {
            // Tester si l'URI matche le pattern
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);

                // Vérifier le middleware si la route est protégée
                if ($route['middleware'] !== null) {
                    $allowed = ($route['middleware'])($request);
                    if (!$allowed) return;
                }

                [$controllerClass, $methodName] = $route['handler'];
                $controller = new $controllerClass();
                $controller->$methodName($request, ...$matches);
                return;
            }
        }
        
        // Aucune route trouvée
        Response::notFound("Route non trouvée : $method $uri");
    }
}
