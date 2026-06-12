<?php
require_once __DIR__ . '/../Core/Database.php';

class EnseignementModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM enseignement ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM enseignement WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByModule(int $moduleId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM enseignement WHERE module_id = ? ORDER BY id ASC");
        $stmt->execute([$moduleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO enseignement (module_id, nom, description) VALUES (:module_id, :nom, :description)");
        $stmt->execute([
            'module_id'   => $data['module_id'],
            'nom'         => $data['nom'],
            'description' => $data['description'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE enseignement SET module_id = :module_id, nom = :nom, description = :description WHERE id = :id");
        return $stmt->execute([
            'module_id'   => $data['module_id'],
            'nom'         => $data['nom'],
            'description' => $data['description'] ?? null,
            'id'          => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM enseignement WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
