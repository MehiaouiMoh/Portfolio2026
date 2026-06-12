<?php
require_once __DIR__ . '/../../src/Core/Database.php';


function seedDB(): void
{
    $schemaPath = __DIR__ . '/../seed.sql';
    try{
        $pdo = Database::getInstance();

        //Contenu du fichier schema.sql : lecture puis renvoie en String
        $schema = file_get_contents($schemaPath);
        //Executer le sql avec pdo
        $pdo->exec($schema);
        echo "✅ Base de données remplie avec succès !\n";
    }catch(PDOException $e){
        die("Erreur lors de l'initialisation de la base de données : " . $e->getMessage());
    }
}

// Si le script est lancé directement, on exécute la fonction
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    seedDB();
}
