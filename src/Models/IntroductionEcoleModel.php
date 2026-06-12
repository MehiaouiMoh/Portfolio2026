<?php
require_once __DIR__ . '/../Core/Database.php';

class IntroductionEcoleModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /*CREATE TABLE IF NOT EXISTS introduction_ecole (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        scolarite_id INTEGER NOT NULL,
        titre_court VARCHAR(100) NOT NULL,
        image VARCHAR(255),
        description_intro TEXT,
        nb_annees INTEGER NOT NULL,
        FOREIGN KEY (scolarite_id) REFERENCES scolarite(id) ON DELETE CASCADE
    ); */

    public function findAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT ie.id, ie.scolarite_id, ie.titre_court, ie.image, ie.description_intro, ie.nb_annees,
                   s.etablissement, s.ville, s.intitule AS formation_intitule, s.niveau AS formation_niveau
            FROM introduction_ecole ie
            JOIN scolarite s ON s.id = ie.scolarite_id
            ORDER BY ie.id ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT ie.id, ie.scolarite_id, ie.titre_court, ie.image, ie.description_intro, ie.nb_annees,
                   s.etablissement, s.ville, s.intitule AS formation_intitule, s.niveau AS formation_niveau
            FROM introduction_ecole ie
            JOIN scolarite s ON s.id = ie.scolarite_id
            WHERE ie.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO introduction_ecole (scolarite_id, titre_court, image, description_intro, nb_annees)
            VALUES (:scolarite_id, :titre_court, :image, :description_intro, :nb_annees)
        ");
        $stmt->execute([
            'scolarite_id'     => $data['scolarite_id'],
            'titre_court'      => $data['titre_court'],
            'image'            => $data['image'] ?? null,
            'description_intro'=> $data['description_intro'] ?? null,
            'nb_annees'        => $data['nb_annees'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE introduction_ecole
            SET scolarite_id = :scolarite_id, titre_court = :titre_court,
                image = :image, description_intro = :description_intro, nb_annees = :nb_annees
            WHERE id = :id
        ");
        return $stmt->execute([
            'scolarite_id'     => $data['scolarite_id'],
            'titre_court'      => $data['titre_court'],
            'image'            => $data['image'] ?? null,
            'description_intro'=> $data['description_intro'] ?? null,
            'nb_annees'        => $data['nb_annees'],
            'id'               => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM introduction_ecole WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
