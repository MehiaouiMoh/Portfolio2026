<?php
require_once __DIR__ . '/../Models/FrameworksModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class FrameworksController
{
    private FrameworksModel $model;

    public function __construct()
    {
        $this->model = new FrameworksModel();
    }

    public function index(Request $request): void
    {
        $frameworks = $this->model->findAll();
        Response::success("Liste des frameworks", $frameworks);
    }

    public function show(Request $request, $id): void
    {
        $framework = $this->model->findById((int) $id);
        if ($framework === null) {
            Response::notFound("Framework n°$id introuvable");
            return;
        }
        Response::success("Framework trouvé", $framework);
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
            $framework = $this->model->findById($newId);
            Response::success("Framework créé", $framework, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Framework n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['name']) || empty($data['categorie_id'])) {
            Response::error("Champs obligatoires manquants : name, categorie_id", 422);
            return;
        }

        try {
            $this->model->update($id, $data);
            $framework = $this->model->findById($id);
            Response::success("Framework mis à jour", $framework);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Framework n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Framework n°$id supprimé");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
