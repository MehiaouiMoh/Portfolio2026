<?php
// Création du model pour les projets
require_once __DIR__ . '/../Core/Database.php';

class ProjetsModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer tous les projets : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT p.*, c.name AS categorie_name
            FROM projet p
            LEFT JOIN categorie_competences c ON p.categorie_id = c.id
            ORDER BY p.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer un projet par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, c.name AS categorie_name
            FROM projet p
            LEFT JOIN categorie_competences c ON p.categorie_id = c.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer un nouveau projet : create($data)
    public function create(array $data): int
    {
       $stmt = $this->pdo->prepare("INSERT INTO projet (user_id, titre, description, but, image_description, difficulte, lien, categorie_id, date_debut, date_fin)
                VALUES (:user_id, :titre, :description, :but, :image_description, :difficulte, :lien, :categorie_id, :date_debut, :date_fin)");

        $stmt->execute([
            'user_id'           => $data['user_id'],
            'titre'             => $data['titre'],
            'description'       => $data['description'],
            'but'               => $data['but'] ?? null,
            'image_description' => $data['image_description'] ?? null,
            'difficulte'        => $data['difficulte'] ?? null,
            'lien'              => $data['lien'] ?? null,
            'categorie_id'      => $data['categorie_id'] ?? null,
            'date_debut'        => $data['date_debut'],
            'date_fin'          => $data['date_fin'] ?? null,
        ]);
        
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour un projet : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE projet SET user_id = :user_id, titre = :titre, description = :description, but = :but, image_description = :image_description, difficulte = :difficulte,
        lien = :lien, categorie_id = :categorie_id, date_debut = :date_debut, date_fin = :date_fin WHERE id = :id");

        return $stmt->execute([
            'user_id'           => $data['user_id'],
            'titre'             => $data['titre'],
            'description'       => $data['description'],
            'but'               => $data['but'] ?? null,
            'image_description' => $data['image_description'] ?? null,
            'difficulte'        => $data['difficulte'] ?? null,
            'lien'              => $data['lien'] ?? null,
            'categorie_id'      => $data['categorie_id'] ?? null,
            'date_debut'        => $data['date_debut'],
            'date_fin'          => $data['date_fin'] ?? null,
            'id'                => $id
        ]);
    }

    //Supprimer un projet : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM projet WHERE id = ?");
        return $stmt->execute([$id]);
    }
}