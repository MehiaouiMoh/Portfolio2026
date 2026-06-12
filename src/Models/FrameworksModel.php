<?php
require_once __DIR__ . '/../Core/Database.php';

class FrameworksModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM frameworks ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM frameworks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO frameworks (name, icon, pourcentage, categorie_id) VALUES (:name, :icon, :pourcentage, :categorie_id)");
        $stmt->execute([
            'name'        => $data['name'],
            'icon'        => $data['icon'] ?? null,
            'pourcentage' => $data['pourcentage'] ?? null,
            'categorie_id' => $data['categorie_id'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE frameworks SET name = :name, icon = :icon, pourcentage = :pourcentage, categorie_id = :categorie_id WHERE id = :id");
        return $stmt->execute([
            'name'        => $data['name'],
            'icon'        => $data['icon'] ?? null,
            'pourcentage' => $data['pourcentage'] ?? null,
            'categorie_id' => $data['categorie_id'],
            'id'          => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM frameworks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
