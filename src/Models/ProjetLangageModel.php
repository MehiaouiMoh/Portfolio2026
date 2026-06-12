<?php
// Création du model pour la liaison projet-langage
require_once __DIR__ . '/../Core/Database.php';

class ProjetLangageModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer toutes les liaisons projet-langage : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM projet_langage ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une liaison projet-langage par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM projet_langage WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle liaison projet-langage : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO projet_langage (projet_id, langage_id)
                VALUES (:projet_id, :langage_id)");
        $stmt->execute([
            'projet_id'  => $data['projet_id'],
            'langage_id' => $data['langage_id'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une liaison projet-langage : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE projet_langage SET projet_id = :projet_id,
                langage_id = :langage_id WHERE id = :id");
        return $stmt->execute([
            'projet_id'  => $data['projet_id'],
            'langage_id' => $data['langage_id'],
            'id'         => $id,
        ]);
    }

    //Supprimer une liaison projet-langage : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM projet_langage WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
