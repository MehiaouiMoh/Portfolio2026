<?php
require_once __DIR__ . '/../Models/VisitesModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class VisitesController
{
    private VisitesModel $model;

    public function __construct()
    {
        $this->model = new VisitesModel();
    }

    public function index(Request $request): void
    {
        $visites = $this->model->findAll();
        Response::success("Liste des visites", $visites);
    }

    public function show(Request $request, $id): void
    {
        $visite = $this->model->findById((int) $id);
        if ($visite === null) {
            Response::notFound("Visite n°$id introuvable");
            return;
        }
        Response::success("Visite trouvée", $visite);
    }

    public function store(Request $request): void
    {
        $data = $request->getBody();

        if (empty($data['ip_address_hash']) || empty($data['page'])) {
            Response::error("Champs obligatoires manquants : ip_address_hash, page", 422);
            return;
        }

        try {
            $newId = $this->model->create($data);
            $visite = $this->model->findById($newId);
            Response::success("Visite enregistrée", $visite, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Visite n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Visite n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
