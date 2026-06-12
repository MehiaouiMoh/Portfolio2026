<?php
// Création du model pour la liaison module-compétence
require_once __DIR__ . '/../Core/Database.php';

class ModuleCompetenceModel
{
    //Attribut pour la connection à la databse
    private PDO $pdo;

    //Constructeur pour initialiser la connection à la database
    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    //Méthode pour récupérer toutes les liaisons module-compétence : findAll()
    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM module_competence ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Méthode pour récupérer une liaison module-compétence par son id : findById($id)
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM module_competence WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    //Créer une nouvelle liaison module-compétence : create($data)
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO module_competence (module_id, competence_id)
                VALUES (:module_id, :competence_id)");
        $stmt->execute([
            'module_id'     => $data['module_id'],
            'competence_id' => $data['competence_id'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    //Mettre à jour une liaison module-compétence : update($id, $data)
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE module_competence SET module_id = :module_id,
                competence_id = :competence_id WHERE id = :id");
        return $stmt->execute([
            'module_id'     => $data['module_id'],
            'competence_id' => $data['competence_id'],
            'id'            => $id,
        ]);
    }

    //Supprimer une liaison module-compétence : delete($id)
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM module_competence WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
