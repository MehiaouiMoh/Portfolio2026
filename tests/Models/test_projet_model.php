<?php

require_once __DIR__ . '/../../src/Models/ProjetsModel.php';

$model = new ProjetsModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$projets = $model->findAll();
echo "Nombre de projets : " . count($projets) . "\n";
print_r($projets);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$projet = $model->findById(1);
print_r($projet);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result);   // doit afficher NULL

