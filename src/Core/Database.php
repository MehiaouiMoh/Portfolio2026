<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/Exceptions/DatabaseException.php';

class Database
{
    // 1. Propriété statique pour stocker l'instance unique
    private static $db = null;

    // 2. Constructeur PRIVÉ (empêche le new Database() depuis l'extérieur)
    private function __construct() {}

    // 3. Méthode statique getInstance() qui :
    //    - crée l'instance si elle n'existe pas
    //    - la renvoie
    public static function getInstance(): PDO
    {
        if (self::$db === null) {
            try {
                // créer le PDO
                self::$db = new PDO('sqlite:' . DB_PATH);
                // configurer ERRMODE_EXCEPTION
                self::$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // configurer FETCH_ASSOC par défaut
                self::$db -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                // activer PRAGMA foreign_keys = ON
                self::$db->exec('PRAGMA foreign_keys = ON');
            } catch (PDOException $e) {
                throw new DatabaseException("Connexion base de données échouée : " . $e->getMessage());
            }
        }
        return self::$db;
    }
}