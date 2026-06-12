<?php

require_once __DIR__ . '/../../src/Core/Router.php';

// Contrôleur factice juste pour le test
class TestController
{
    public function index(Request $request): void
    {
        Response::success("Liste de tous les projets");
    }
    
    public function show(Request $request, $id): void
    {
        Response::success("Projet n°$id", ['id' => $id]);
    }
    
    public function store(Request $request): void
    {
        Response::success("Projet créé", $request->getBody(), 201);
    }
}

$router = new Router();

// Enregistrement des routes
$router->get('/api/projets', [TestController::class, 'index']);
$router->get('/api/projets/{id}', [TestController::class, 'show']);
$router->post('/api/projets', [TestController::class, 'store']);

// Pour tester en faisant varier l'URI sans setup Apache
class FakeRequest extends Request {
    public function __construct(private string $fakeUri, private string $fakeMethod = 'GET') {
        parent::__construct();
    }
    public function getUri(): string { return $this->fakeUri; }
    public function getMethod(): string { return $this->fakeMethod; }
}

// Simule différentes requêtes
$tests = [
    new FakeRequest('/api/projets'),
    new FakeRequest('/api/projets/42'),
    new FakeRequest('/api/inexistant'),
];

// Lance UN seul test (le Response::exit() coupe le script après)
$router->dispatch($tests[2]);   // change l'index pour tester d'autres routes