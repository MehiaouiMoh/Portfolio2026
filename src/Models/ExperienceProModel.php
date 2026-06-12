<?php
// Création du model pour les expériences professionnelles
require_once __DIR__ . '/../Core/Database.php';

class ExperienceProModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer toutes les expériences professionnelles : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM experience_pro ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une expérience professionnelle par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM experience_pro WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle expérience professionnelle : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO experience_pro (user_id, intitule, entreprise, ville, description, date_debut, date_fin, type_contrat)
                VALUES (:user_id, :intitule, :entreprise, :ville, :description, :date_debut, :date_fin, :type_contrat)");
        $stmt->execute([
            'user_id'       => $data['user_id'],
            'intitule'      => $data['intitule'],
            'entreprise'    => $data['entreprise'],
            'ville'         => $data['ville'],
            'description'   => $data['description'] ?? null,
            'date_debut'    => $data['date_debut'],
            'date_fin'      => $data['date_fin'] ?? null,
            'type_contrat'  => $data['type_contrat'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une expérience professionnelle : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE experience_pro SET user_id = :user_id, intitule = :intitule,
                entreprise = :entreprise, ville = :ville, description = :description,
                date_debut = :date_debut, date_fin = :date_fin, type_contrat = :type_contrat WHERE id = :id");
        return $stmt->execute([
            'user_id'       => $data['user_id'],
            'intitule'      => $data['intitule'],
            'entreprise'    => $data['entreprise'],
            'ville'         => $data['ville'],
            'description'   => $data['description'] ?? null,
            'date_debut'    => $data['date_debut'],
            'date_fin'      => $data['date_fin'] ?? null,
            'type_contrat'  => $data['type_contrat'] ?? null,
            'id'            => $id,
        ]);
    }

    //Supprimer une expérience professionnelle : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM experience_pro WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
