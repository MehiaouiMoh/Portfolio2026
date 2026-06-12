<?php
// Création du model pour les langages
require_once __DIR__ . '/../Core/Database.php';

class LangagesModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer tous les langages : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM langages ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer un langage par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM langages WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer un nouveau langage : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO langages (name, icon, pourcentage, categorie_id) VALUES (:name, :icon, :pourcentage, :categorie_id)");
        $stmt->execute([
            'name'        => $data['name'],
            'icon'        => $data['icon'] ?? null,
            'pourcentage' => $data['pourcentage'] ?? null,
            'categorie_id' => $data['categorie_id'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour un langage : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE langages SET name = :name, icon = :icon, pourcentage = :pourcentage, categorie_id = :categorie_id WHERE id = :id");
        return $stmt->execute([
            'name'        => $data['name'],
            'icon'        => $data['icon'] ?? null,
            'pourcentage' => $data['pourcentage'] ?? null,
            'categorie_id' => $data['categorie_id'],
            'id'          => $id,
        ]);
    }

    //Supprimer un langage : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM langages WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
