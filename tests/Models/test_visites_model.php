<?php

require_once __DIR__ . '/../../src/Models/VisitesModel.php';

$model = new VisitesModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$visites = $model->findAll();
echo "Nombre de visites : " . count($visites) . "\n";
print_r($visites);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$visite = $model->findById(1);
print_r($visite);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create
echo "\n=== create ===\n";
$newId = $model->create([
    'ip_address_hash' => 'c1d2e3f4a5b6789012345678901234567890abcdef1234567890abcdef123456',
    'page'            => '/contact',
    'user_agent'      => 'Mozilla/5.0 (Test)',
    'referrer'        => 'https://example.com',
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'ip_address_hash' => 'c1d2e3f4a5b6789012345678901234567890abcdef1234567890abcdef123456',
    'page'            => '/about',
    'user_agent'      => 'Mozilla/5.0 (Test Modifié)',
    'referrer'        => null,
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
