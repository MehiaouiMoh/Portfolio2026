<?php
require_once __DIR__ . '/../Models/EnseignementModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class EnseignementController
{
    private EnseignementModel $model;

    public function __construct()
    {
        $this->model = new EnseignementModel();
    }

    public function index(Request $request): void
    {
        $enseignements = $this->model->findAll();
        Response::success("Liste des enseignements", $enseignements);
    }

    public function show(Request $request, $id): void
    {
        $enseignement = $this->model->findById((int) $id);
        if ($enseignement === null) {
            Response::notFound("Enseignement n°$id introuvable");
            return;
        }
        Response::success("Enseignement trouvé", $enseignement);
    }

    public function byModule(Request $request, $moduleId): void
    {
        $enseignements = $this->model->findByModule((int) $moduleId);
        Response::success("Enseignements du module n°$moduleId", $enseignements);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['module_id']) || empty($data['nom'])) {
            Response::error("Champs obligatoires manquants : module_id, nom", 422);
            return;
        }

        try {
            $newId = $this->model->create($data);
            $enseignement = $this->model->findById($newId);
            Response::success("Enseignement créé", $enseignement, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Enseignement n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['module_id']) || empty($data['nom'])) {
            Response::error("Champs obligatoires manquants : module_id, nom", 422);
            return;
        }

        try {
            $this->model->update($id, $data);
            $enseignement = $this->model->findById($id);
            Response::success("Enseignement mis à jour", $enseignement);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Enseignement n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Enseignement n°$id supprimé");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
