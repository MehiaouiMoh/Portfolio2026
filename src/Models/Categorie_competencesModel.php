<?php
// Création du model pour les catégories de compétences
require_once __DIR__ . '/../Core/Database.php';

class Categorie_competencesModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer toutes les catégories de compétences : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categorie_competences ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une catégorie de compétences par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categorie_competences WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle catégorie de compétences : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO categorie_competences (name) VALUES (:name)");
        $stmt->execute([
            'name' => $data['name'],
        ]);
        
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une catégorie de compétences : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE categorie_competences SET name = :name WHERE id = :id");
        return $stmt->execute([
            'name' => $data['name'],
            'id' => $id
        ]);
    }

    //Supprimer une catégorie de compétences : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM categorie_competences WHERE id = ?");
        return $stmt->execute([$id]);
    }
}