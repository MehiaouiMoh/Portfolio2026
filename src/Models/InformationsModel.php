<?php
// Création du model pour les informations
require_once __DIR__ . '/../Core/Database.php';

class InformationsModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer toutes les informations : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM informations ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une information par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM informations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle information : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO informations (user_id, nom, prenom, telephone, description, avatar)
                VALUES (:user_id, :nom, :prenom, :telephone, :description, :avatar)");
        $stmt->execute([
            'user_id'     => $data['user_id'],
            'nom'         => $data['nom'],
            'prenom'      => $data['prenom'],
            'telephone'   => $data['telephone'] ?? null,
            'description' => $data['description'],
            'avatar'      => $data['avatar'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une information : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE informations SET user_id = :user_id, nom = :nom, prenom = :prenom,
                telephone = :telephone, description = :description, avatar = :avatar WHERE id = :id");
        return $stmt->execute([
            'user_id'     => $data['user_id'],
            'nom'         => $data['nom'],
            'prenom'      => $data['prenom'],
            'telephone'   => $data['telephone'] ?? null,
            'description' => $data['description'],
            'avatar'      => $data['avatar'] ?? null,
            'id'          => $id,
        ]);
    }

    //Supprimer une information : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM informations WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
