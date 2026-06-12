<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/init_db.php';   // ← charge la fonction initDb, n'exécute rien
require_once __DIR__ . '/seed_db.php';   // ← charge la fonction seedDb, n'exécute rien

function resetDB():void
{
    // 1. Supprimer le fichier de la base de données si il existe
    if (file_exists(DB_PATH)) {
        unlink(DB_PATH);
        echo "🗑️ Base de données supprimée avec succès !\n";
    } else {
        die("❌ Impossible de supprimer la BDD (fichier verrouillé ?)\n");
    }
    // 2. Initialiser la base de données
    initDB();
    // 3. Remplir la base de données
    seedDB();

    echo "\n✨ Reset terminé !\n";
}

// Si le script est lancé directement, on exécute la fonction
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    resetDB();
}
