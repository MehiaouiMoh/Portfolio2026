<?php

require_once __DIR__ . '/../../src/Models/UsersModel.php';

$model = new UsersModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$users = $model->findAll();
echo "Nombre d'utilisateurs : " . count($users) . "\n";
print_r($users);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$user = $model->findById(1);
print_r($user);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create
echo "\n=== create ===\n";
$newId = $model->create([
    'username' => 'test_user',
    'email'    => 'test_user@test.com',
    'password' => 'secret123',
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'username' => 'test_user_modifie',
    'email'    => 'test_user_modifie@test.com',
    'password' => 'newpassword456',
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
