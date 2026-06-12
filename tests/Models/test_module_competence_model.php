<?php

require_once __DIR__ . '/../../src/Models/ModuleCompetenceModel.php';

$model = new ModuleCompetenceModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$liaisons = $model->findAll();
echo "Nombre de liaisons module-compétence : " . count($liaisons) . "\n";
print_r($liaisons);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$liaison = $model->findById(1);
print_r($liaison);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create — combinaison (2,1) n'existe pas dans les seeds
echo "\n=== create ===\n";
$newId = $model->create([
    'module_id'     => 2,
    'competence_id' => 1,
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'module_id'     => 2,
    'competence_id' => 4,
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
