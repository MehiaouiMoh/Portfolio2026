<?php
require_once __DIR__ . '/../Models/CompetencesModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class CompetencesController
{
    private CompetencesModel $model;

    public function __construct()
    {
        $this->model = new CompetencesModel();
    }

    public function index(Request $request): void
    {
        $competences = $this->model->findAll();
        Response::success("Liste des compétences", $competences);
    }

    public function show(Request $request, $id): void
    {
        $competence = $this->model->findById((int) $id);
        if ($competence === null) {
            Response::notFound("Compétence n°$id introuvable");
            return;
        }
        Response::success("Compétence trouvée", $competence);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['categorie_id']) || empty($data['nom'])) {
            Response::error("Champs obligatoires manquants : categorie_id, nom", 422);
            return;
        }

        $data['user_id'] = 1;

        try {
            $newId = $this->model->create($data);
            $competence = $this->model->findById($newId);
            Response::success("Compétence créée", $competence, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Compétence n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['categorie_id'])) {
            Response::error("Champs obligatoires manquants : categorie_id", 422);
            return;
        }

        $data['user_id'] = 1;

        try {
            $this->model->update($id, $data);
            $competence = $this->model->findById($id);
            Response::success("Compétence mise à jour", $competence);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Compétence n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Compétence n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
