<?php
require_once __DIR__ . '/../../src/Core/Database.php';

$pdo = Database::getInstance();
$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll();

echo "📊 " . count($tables) . " tables présentes :\n";
foreach ($tables as $t) echo "  - " . $t['name'] . "\n";