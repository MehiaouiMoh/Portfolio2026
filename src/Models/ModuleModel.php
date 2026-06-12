<?php
// Création du model pour les modules
require_once __DIR__ . '/../Core/Database.php';

class ModuleModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer tous les modules : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM module ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer un module par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM module WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer un nouveau module : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO module (scolarite_id, intitule, niveau, description)
                VALUES (:scolarite_id, :intitule, :niveau, :description)");
        $stmt->execute([
            'scolarite_id' => $data['scolarite_id'],
            'intitule'     => $data['intitule'],
            'niveau'       => $data['niveau'],
            'description'  => $data['description'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour un module : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE module SET scolarite_id = :scolarite_id, intitule = :intitule,
                niveau = :niveau, description = :description WHERE id = :id");
        return $stmt->execute([
            'scolarite_id' => $data['scolarite_id'],
            'intitule'     => $data['intitule'],
            'niveau'       => $data['niveau'],
            'description'  => $data['description'] ?? null,
            'id'           => $id,
        ]);
    }

    //Supprimer un module : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM module WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
