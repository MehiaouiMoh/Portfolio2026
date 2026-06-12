<?php
require_once __DIR__ . '/../Models/CarriereCompetenceModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class CarriereCompetenceController
{
    private CarriereCompetenceModel $model;

    public function __construct()
    {
        $this->model = new CarriereCompetenceModel();
    }

    public function index(Request $request): void
    {
        $carriereCompetences = $this->model->findAll();
        Response::success("Liste des liaisons carrière/compétence", $carriereCompetences);
    }

    public function show(Request $request, $id): void
    {
        $carriereCompetence = $this->model->findById((int) $id);
        if ($carriereCompetence === null) {
            Response::notFound("Liaison carrière/compétence n°$id introuvable");
            return;
        }
        Response::success("Liaison carrière/compétence trouvée", $carriereCompetence);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['carriere_id']) || empty($data['competence_id'])) {
            Response::error("Champs obligatoires manquants : carriere_id, competence_id", 422);
            return;
        }

        try {
            $newId = $this->model->create($data);
            $carriereCompetence = $this->model->findById($newId);
            Response::success("Liaison carrière/compétence créée", $carriereCompetence, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Liaison carrière/compétence n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Liaison carrière/compétence n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
