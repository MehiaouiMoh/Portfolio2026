<?php

require_once __DIR__ . '/../../src/Models/ImageProjetModel.php';

$model = new ImageProjetModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$images = $model->findAll();
echo "Nombre d\'images de projet : " . count($images) . "\n";
print_r($images);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$image = $model->findById(1);
print_r($image);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create (projet_id=1 existe dans les seeds)
echo "\n=== create ===\n";
$newId = $model->create([
    'projet_id' => 1,
    'image_url' => '/uploads/projets/test_image.jpg',
    'legende'   => 'Image de test',
    'ordre'     => 99,
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'projet_id' => 1,
    'image_url' => '/uploads/projets/test_image_modifie.jpg',
    'legende'   => null,
    'ordre'     => 100,
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
