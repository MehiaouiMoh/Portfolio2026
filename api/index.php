<?php
//Rôle : assembler les morceaux et lancer le moteur.
//Importer le router puis en créer un :
require_once __DIR__.'/../src/Core/Router.php';
$router = new Router();

// 3. Charger les routes
require_once __DIR__ . '/routes.php';

// 4. Créer la requête et dispatcher
$request = new Request();
$router->dispatch($request);
