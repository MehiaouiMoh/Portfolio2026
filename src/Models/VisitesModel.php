<?php
// Création du model pour les visites
require_once __DIR__ . '/../Core/Database.php';

class VisitesModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer toutes les visites : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM visites ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une visite par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM visites WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle visite : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO visites (ip_address_hash, page, user_agent, referrer)
                VALUES (:ip_address_hash, :page, :user_agent, :referrer)");
        $stmt->execute([
            'ip_address_hash' => $data['ip_address_hash'],
            'page'            => $data['page'],
            'user_agent'      => $data['user_agent'] ?? null,
            'referrer'        => $data['referrer'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une visite : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE visites SET ip_address_hash = :ip_address_hash, page = :page,
                user_agent = :user_agent, referrer = :referrer WHERE id = :id");
        return $stmt->execute([
            'ip_address_hash' => $data['ip_address_hash'],
            'page'            => $data['page'],
            'user_agent'      => $data['user_agent'] ?? null,
            'referrer'        => $data['referrer'] ?? null,
            'id'              => $id,
        ]);
    }

    //Supprimer une visite : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM visites WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
