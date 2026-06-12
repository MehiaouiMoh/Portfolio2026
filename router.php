<?php
/**
 * Router pour le serveur de développement PHP intégré.
 *
 * Permet de lancer UNE SEULE commande pour servir à la fois :
 *   - Les fichiers statiques (HTML, CSS, JS, images...)
 *   - L'API PHP (routes /api/...)
 *
 * Usage (depuis la racine du projet) :
 *   php -S localhost:8000 router.php
 *
 * Puis ouvrir : http://localhost:8000/admin/login.html
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Toutes les requêtes /api/* sont transmises au front controller de l'API.
// Le Router.php lit $_SERVER['REQUEST_URI'] directement, donc tout fonctionne.
if (str_starts_with($uri, '/api/')) {
    require __DIR__ . '/api/index.php';
    return true;
}

// Pour tout le reste (fichiers HTML, assets...) : le serveur PHP
// les sert directement s'ils existent sur le disque.
return false;
