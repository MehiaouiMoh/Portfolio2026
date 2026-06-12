<?php

require_once __DIR__ . '/../../src/Models/CarriereViseeModel.php';

$model = new CarriereViseeModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$carrieres = $model->findAll();
echo "Nombre de carrières visées : " . count($carrieres) . "\n";
print_r($carrieres);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$carriere = $model->findById(1);
print_r($carriere);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create
echo "\n=== create ===\n";
$newId = $model->create([
    'user_id'     => 1,
    'intitule'    => 'Ingénieur DevOps',
    'description' => 'Automatisation et CI/CD dans un environnement cloud.',
    'interet'     => 'Docker, Kubernetes, CI/CD',
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'user_id'     => 1,
    'intitule'    => 'Ingénieur DevOps (modifié)',
    'description' => 'Description mise à jour.',
    'interet'     => null,
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
