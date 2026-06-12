<?php
require_once __DIR__ . '/../Models/ModuleCompetenceModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class ModuleCompetenceController
{
    private ModuleCompetenceModel $model;

    public function __construct()
    {
        $this->model = new ModuleCompetenceModel();
    }

    public function index(Request $request): void
    {
        $moduleCompetences = $this->model->findAll();
        Response::success("Liste des liaisons module/compétence", $moduleCompetences);
    }

    public function show(Request $request, $id): void
    {
        $moduleCompetence = $this->model->findById((int) $id);
        if ($moduleCompetence === null) {
            Response::notFound("Liaison module/compétence n°$id introuvable");
            return;
        }
        Response::success("Liaison module/compétence trouvée", $moduleCompetence);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['module_id']) || empty($data['competence_id'])) {
            Response::error("Champs obligatoires manquants : module_id, competence_id", 422);
            return;
        }

        try {
            $newId = $this->model->create($data);
            $moduleCompetence = $this->model->findById($newId);
            Response::success("Liaison module/compétence créée", $moduleCompetence, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Liaison module/compétence n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Liaison module/compétence n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
