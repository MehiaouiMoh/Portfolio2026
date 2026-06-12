<?php
// Création du model pour les images de projet
require_once __DIR__ . '/../Core/Database.php';

class ImageProjetModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer toutes les images de projet : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM image_projet ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une image de projet par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM image_projet WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle image de projet : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO image_projet (projet_id, image_url, legende, ordre)
                VALUES (:projet_id, :image_url, :legende, :ordre)");
        $stmt->execute([
            'projet_id' => $data['projet_id'],
            'image_url' => $data['image_url'],
            'legende'   => $data['legende'] ?? null,
            'ordre'     => $data['ordre'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une image de projet : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE image_projet SET projet_id = :projet_id, image_url = :image_url,
                legende = :legende, ordre = :ordre WHERE id = :id");
        return $stmt->execute([
            'projet_id' => $data['projet_id'],
            'image_url' => $data['image_url'],
            'legende'   => $data['legende'] ?? null,
            'ordre'     => $data['ordre'],
            'id'        => $id,
        ]);
    }

    //Supprimer une image de projet : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM image_projet WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
