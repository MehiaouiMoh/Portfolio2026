<?php

require_once __DIR__ . '/../../src/Models/LangagesModel.php';

$model = new LangagesModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$langages = $model->findAll();
echo "Nombre de langages : " . count($langages) . "\n";
print_r($langages);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$langage = $model->findById(1);
print_r($langage);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create
echo "\n=== create ===\n";
$newId = $model->create([
    'name' => 'TypeScript',
    'icon' => '/icons/typescript.svg',
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'name' => 'TypeScript (modifié)',
    'icon' => '/icons/ts.svg',
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
