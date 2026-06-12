<?php

require_once __DIR__ . '/../../src/Models/Categorie_competencesModel.php';

$model = new Categorie_competencesModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$categories = $model->findAll();
echo "Nombre de catégories : " . count($categories) . "\n";
print_r($categories);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$categorie = $model->findById(1);
print_r($categorie);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result);   // doit afficher NULL