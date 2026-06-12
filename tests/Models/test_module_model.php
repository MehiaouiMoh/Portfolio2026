<?php

require_once __DIR__ . '/../../src/Models/ModuleModel.php';

$model = new ModuleModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$modules = $model->findAll();
echo "Nombre de modules : " . count($modules) . "\n";
print_r($modules);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$module = $model->findById(1);
print_r($module);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create (scolarite_id=1 existe dans les seeds)
echo "\n=== create ===\n";
$newId = $model->create([
    'scolarite_id' => 1,
    'intitule'     => 'Sécurité informatique',
    'niveau'       => 'Intermédiaire',
    'description'  => 'Cryptographie, pare-feu, gestion des vulnérabilités.',
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'scolarite_id' => 1,
    'intitule'     => 'Sécurité informatique (modifié)',
    'niveau'       => 'Avancé',
    'description'  => null,
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
