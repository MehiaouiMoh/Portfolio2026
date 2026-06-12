<?php
// Création du model pour les catégories de compétences
require_once __DIR__ . '/../Core/Database.php';

class UsersModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /*CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);*/

    //Méthode pour récupérer toutes les catégories de compétences : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM users');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une catégorie de compétences par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : null;
    }

    //Méthode pour récupérer un user par son email : findByEmail($email)
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : null;
    }

    //Mettre à jour une catégorie de compétences : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id');
        return $stmt->execute([
            'id' => $id,
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
    }

}
