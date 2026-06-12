<?php

require_once __DIR__ . '/../../src/Models/InformationsModel.php';

$model = new InformationsModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$infos = $model->findAll();
echo "Nombre d'informations : " . count($infos) . "\n";
print_r($infos);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$info = $model->findById(1);
print_r($info);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create (user_id=1 existe dans les seeds)
echo "\n=== create ===\n";
$newId = $model->create([
    'user_id'     => 1,
    'nom'         => 'Martin',
    'prenom'      => 'Alice',
    'telephone'   => '0612345678',
    'description' => 'Développeuse passionnée.',
    'avatar'      => '/uploads/avatars/alice.jpg',
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'user_id'     => 1,
    'nom'         => 'Martin',
    'prenom'      => 'Alice (modifié)',
    'telephone'   => null,
    'description' => 'Description mise à jour.',
    'avatar'      => null,
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
