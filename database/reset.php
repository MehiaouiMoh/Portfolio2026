<?php
$dbPath    = __DIR__ . '/portfolio.sqlite';
$schemaPath = __DIR__ . '/schema.sql';
$seedPath   = __DIR__ . '/seed.sql';

// Supprime le fichier SQLite existant
if (file_exists($dbPath)) {
    unlink($dbPath);
    echo "Base supprimée.\n";
}

// Recrée la connexion (crée un nouveau fichier vide)
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA foreign_keys = ON');

// Exécute schema.sql
$pdo->exec(file_get_contents($schemaPath));
echo "Tables créées.\n";

// Exécute seed.sql
$pdo->exec(file_get_contents($seedPath));
echo "Données insérées.\n";

echo "Reset terminé.\n";
