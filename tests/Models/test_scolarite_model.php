<?php

require_once __DIR__ . '/../../src/Models/ScolariteModel.php';

$model = new ScolariteModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$scolarites = $model->findAll();
echo "Nombre d'entrées scolarité : " . count($scolarites) . "\n";
print_r($scolarites);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$scolarite = $model->findById(1);
print_r($scolarite);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create
echo "\n=== create ===\n";
$newId = $model->create([
    'user_id'       => 1,
    'intitule'      => 'Licence Informatique',
    'niveau'        => 'Bac+3',
    'etablissement' => 'Université Test',
    'ville'         => 'Bordeaux',
    'description'   => 'Licence généraliste en informatique.',
    'date_debut'    => '2023-09-01',
    'date_fin'      => null,
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'user_id'       => 1,
    'intitule'      => 'Licence Informatique (modifié)',
    'niveau'        => 'Bac+3',
    'etablissement' => 'Université Test Modifiée',
    'ville'         => 'Toulouse',
    'description'   => 'Description mise à jour.',
    'date_debut'    => '2023-09-01',
    'date_fin'      => '2024-06-30',
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
