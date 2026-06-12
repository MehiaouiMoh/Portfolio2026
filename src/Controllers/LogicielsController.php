<?php
require_once __DIR__ . '/../Models/LogicielsModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class LogicielsController
{
    private LogicielsModel $model;

    public function __construct()
    {
        $this->model = new LogicielsModel();
    }

    public function index(Request $request): void
    {
        $logiciels = $this->model->findAll();
        Response::success("Liste des logiciels", $logiciels);
    }

    public function show(Request $request, $id): void
    {
        $logiciel = $this->model->findById((int) $id);
        if ($logiciel === null) {
            Response::notFound("Logiciel n°$id introuvable");
            return;
        }
        Response::success("Logiciel trouvé", $logiciel);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['name']) || empty($data['categorie_id'])) {
            Response::error("Champs obligatoires manquants : name, categorie_id", 422);
            return;
        }

        try {
            $newId = $this->model->create($data);
            $logiciel = $this->model->findById($newId);
            Response::success("Logiciel créé", $logiciel, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Logiciel n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['name']) || empty($data['categorie_id'])) {
            Response::error("Champs obligatoires manquants : name, categorie_id", 422);
            return;
        }

        try {
            $this->model->update($id, $data);
            $logiciel = $this->model->findById($id);
            Response::success("Logiciel mis à jour", $logiciel);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Logiciel n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Logiciel n°$id supprimé");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
