<?php
// Création du model pour la liaison carrière-compétence
require_once __DIR__ . '/../Core/Database.php';

class CarriereCompetenceModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer toutes les liaisons carrière-compétence : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM carriere_competence ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une liaison carrière-compétence par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM carriere_competence WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle liaison carrière-compétence : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO carriere_competence (carriere_id, competence_id)
                VALUES (:carriere_id, :competence_id)");
        $stmt->execute([
            'carriere_id'   => $data['carriere_id'],
            'competence_id' => $data['competence_id'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une liaison carrière-compétence : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE carriere_competence SET carriere_id = :carriere_id,
                competence_id = :competence_id WHERE id = :id");
        return $stmt->execute([
            'carriere_id'   => $data['carriere_id'],
            'competence_id' => $data['competence_id'],
            'id'            => $id,
        ]);
    }

    //Supprimer une liaison carrière-compétence : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM carriere_competence WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
