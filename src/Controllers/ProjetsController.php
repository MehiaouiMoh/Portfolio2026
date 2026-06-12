<?php
require_once __DIR__ . '/../Models/ProjetsModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class ProjetsController
{
    private ProjetsModel $model;

    public function __construct()
    {
        $this->model = new ProjetsModel();
    }

    public function index(Request $request): void
    {
        $projets = $this->model->findAll();
        Response::success("Liste des projets", $projets);
    }

    public function show(Request $request, $id): void
    {
        $projet = $this->model->findById((int) $id);
        if ($projet === null) {
            Response::notFound("Projet n°$id introuvable");
            return;
        }
        Response::success("Projet trouvé", $projet);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['titre']) || empty($data['description']) || empty($data['date_debut'])) {
            Response::error("Champs obligatoires manquants : titre, description, date_debut", 422);
            return;
        }

        // user_id en dur pour l'instant (auth plus tard)
        $data['user_id'] = 1;

        try {
            $newId = $this->model->create($data);
            $projet = $this->model->findById($newId);
            Response::success("Projet créé", $projet, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Projet n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['titre']) || empty($data['description']) || empty($data['date_debut'])) {
            Response::error("Champs obligatoires manquants : titre, description, date_debut", 422);
            return;
        }

        $data['user_id'] = 1;

        try {
            $this->model->update($id, $data);
            $projet = $this->model->findById($id);
            Response::success("Projet mis à jour", $projet);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Projet n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Projet n°$id supprimé");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
