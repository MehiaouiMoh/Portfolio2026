<?php
// Création du model pour les catégories de compétences
require_once __DIR__ . '/../Core/Database.php';

class CompetencesModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }
    // CREATE TABLE IF NOT EXISTS competences (
    /*id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    categorie_id INTEGER NOT NULL,
    pourcentage INTEGER NULL CHECK(pourcentage >= 0 AND pourcentage <= 100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categorie_competences(id) ON DELETE CASCADE*/
    
    //Méthode pour récupérer toutes les compétences : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM competences ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une compétence par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM competences WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle compétence : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO competences (user_id, categorie_id, nom, pourcentage) VALUES (:user_id, :categorie_id, :nom, :pourcentage)");
        $stmt->execute([
            'user_id'     => $data['user_id'],
            'categorie_id' => $data['categorie_id'],
            'nom'         => $data['nom'],
            'pourcentage' => $data['pourcentage'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une compétence : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE competences SET user_id = :user_id, categorie_id = :categorie_id, nom = :nom, pourcentage = :pourcentage WHERE id = :id");
        return $stmt->execute([
            'user_id'     => $data['user_id'],
            'categorie_id' => $data['categorie_id'],
            'nom'         => $data['nom'],
            'pourcentage' => $data['pourcentage'] ?? null,
            'id'          => $id,
        ]);
    }

    //Supprimer une compétence : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM competences WHERE id = ?");
        return $stmt->execute([$id]);
    }
}