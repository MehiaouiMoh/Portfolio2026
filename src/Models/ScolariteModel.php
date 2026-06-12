<?php
// Création du model pour les catégories de compétences
require_once __DIR__ . '/../Core/Database.php';

class ScolariteModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /*CREATE TABLE IF NOT EXISTS scolarite (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        intitule VARCHAR(255) NOT NULL,
        niveau VARCHAR(255) NOT NULL,
        etablissement VARCHAR(255) NOT NULL,
        ville VARCHAR(255) NOT NULL,
        description TEXT,
        date_debut DATE NOT NULL,
        date_fin DATE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ); */
    //Méthode pour récupérer toutes les scolarités : findAll()
    public function findAll(): array
    {
       $stmt = $this->pdo->query("SELECT * FROM scolarite ORDER BY id DESC");
       return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une scolarité par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM scolarite WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle scolarité : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO scolarite (user_id, intitule, niveau, etablissement, ville, description, date_debut, date_fin) VALUES (:user_id, :intitule, :niveau, :etablissement, :ville, :description, :date_debut, :date_fin)");
        $stmt->execute([
            'user_id' => $data['user_id'],
            'intitule' => $data['intitule'],
            'niveau' => $data['niveau'],
            'etablissement' => $data['etablissement'],
            'ville' => $data['ville'],
            'description' => $data['description'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin']
        ]);
        
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une scolarité : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE scolarite SET user_id = :user_id, intitule = :intitule, niveau = :niveau, etablissement = :etablissement, ville = :ville, description = :description, date_debut = :date_debut, date_fin = :date_fin WHERE id = :id");
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'intitule' => $data['intitule'],
            'niveau' => $data['niveau'],
            'etablissement' => $data['etablissement'],
            'ville' => $data['ville'],
            'description' => $data['description'],
            'date_debut' => $data['date_debut'],
            'date_fin' => $data['date_fin'],
            'id' => $id
        ]);
    }

    //Supprimer une scolarité : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM scolarite WHERE id = ?");
        return $stmt->execute([$id]);
    }
}