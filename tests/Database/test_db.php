<?php

require_once __DIR__ . '/../../src/Core/Database.php';

$pdo = Database::getInstance();
echo "✅ Connexion réussie !\n";
echo "Driver : " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";

// Vérifier que les FK sont activées
$result = $pdo->query("PRAGMA foreign_keys")->fetch();
echo "Foreign keys : " . ($result['foreign_keys'] ? "ACTIVÉES ✅" : "DÉSACTIVÉES ❌") . "\n";