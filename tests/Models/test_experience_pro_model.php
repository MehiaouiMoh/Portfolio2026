<?php

require_once __DIR__ . '/../../src/Models/ExperienceProModel.php';

$model = new ExperienceProModel();

// Test 1 : findAll
echo "=== findAll ===\n";
$experiences = $model->findAll();
echo "Nombre d'expériences pro : " . count($experiences) . "\n";
print_r($experiences);

// Test 2 : findById sur un ID existant
echo "\n=== findById(1) ===\n";
$experience = $model->findById(1);
print_r($experience);

// Test 3 : findById sur un ID inexistant
echo "\n=== findById(999) ===\n";
$result = $model->findById(999);
var_dump($result); // doit afficher NULL

// Test 4 : create
echo "\n=== create ===\n";
$newId = $model->create([
    'user_id'      => 1,
    'intitule'     => 'Développeur PHP',
    'entreprise'   => 'TestCorp',
    'ville'        => 'Marseille',
    'description'  => 'Développement d\'applications web en PHP.',
    'date_debut'   => '2025-01-01',
    'date_fin'     => null,
    'type_contrat' => 'CDI',
]);
echo "Nouvel ID inséré : $newId\n";
print_r($model->findById($newId));

// Test 5 : update
echo "\n=== update($newId) ===\n";
$updated = $model->update($newId, [
    'user_id'      => 1,
    'intitule'     => 'Développeur PHP Senior',
    'entreprise'   => 'TestCorp',
    'ville'        => 'Nice',
    'description'  => null,
    'date_debut'   => '2025-01-01',
    'date_fin'     => '2025-12-31',
    'type_contrat' => 'CDD',
]);
echo "Mise à jour réussie : " . ($updated ? "OUI ✅" : "NON ❌") . "\n";
print_r($model->findById($newId));

// Test 6 : delete
echo "\n=== delete($newId) ===\n";
$deleted = $model->delete($newId);
echo "Suppression réussie : " . ($deleted ? "OUI ✅" : "NON ❌") . "\n";
var_dump($model->findById($newId)); // doit afficher NULL
