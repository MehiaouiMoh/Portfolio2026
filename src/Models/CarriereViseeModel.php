<?php
// Création du model pour les carrières visées
require_once __DIR__ . '/../Core/Database.php';

class CarriereViseeModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer toutes les carrières visées : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM carriere_visee ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une carrière visée par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM carriere_visee WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle carrière visée : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO carriere_visee (user_id, intitule, description, interet)
                VALUES (:user_id, :intitule, :description, :interet)");
        $stmt->execute([
            'user_id'     => $data['user_id'],
            'intitule'    => $data['intitule'],
            'description' => $data['description'],
            'interet'     => $data['interet'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une carrière visée : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE carriere_visee SET user_id = :user_id, intitule = :intitule,
                description = :description, interet = :interet WHERE id = :id");
        return $stmt->execute([
            'user_id'     => $data['user_id'],
            'intitule'    => $data['intitule'],
            'description' => $data['description'],
            'interet'     => $data['interet'] ?? null,
            'id'          => $id,
        ]);
    }

    //Supprimer une carrière visée : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM carriere_visee WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
