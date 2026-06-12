<?php

class Request
{
    private string $method;
    private string $uri;
    private array $body;
    private array $queryParams;
    private array $headers;
    
    public function __construct()
    {
        // 1. Récupérer la méthode HTTP
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // 2. Récupérer l'URI SANS la query string
        //    Utilise parse_url() avec PHP_URL_PATH
        $this->uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        
        // 3. Récupérer les query params ($_GET)
        $this->queryParams = $_GET;
        
        // 4. Parser le body JSON
        //    Lis php://input avec file_get_contents
        //    Parse avec json_decode($..., true)
        //    Si null (body vide ou invalide), mets un tableau vide
        $rawBody = file_get_contents('php://input');
        $this->body = json_decode($rawBody, true) ?? [];
        
        // 5. Récupérer les headers
        //    Utilise getallheaders() si dispo
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];
    }
    
    public function getMethod(): string
    {
        return $this->method;
    }
    
    public function getUri(): string
    {
        return $this->uri;
    }
    
    public function getBody(): array
    {
        return $this->body;
    }
    
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }
    
    /**
     * Retourne la valeur d'un header (case-insensitive)
     * ou null s'il n'existe pas
     */
    public function getHeader(string $name): ?string
    {
        // Les headers HTTP sont insensibles à la casse,
        // mais PHP les garde tels quels.
        // Astuce : parcourir avec une comparaison strtolower
        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === strtolower($name)) {
                return $value;
            }
        }
        return null;
    }
}
