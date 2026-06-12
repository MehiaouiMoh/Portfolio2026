<?php
require_once __DIR__ . '/../Models/ModuleModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class ModuleController
{
    private ModuleModel $model;

    public function __construct()
    {
        $this->model = new ModuleModel();
    }

    public function index(Request $request): void
    {
        $modules = $this->model->findAll();
        Response::success("Liste des modules", $modules);
    }

    public function show(Request $request, $id): void
    {
        $module = $this->model->findById((int) $id);
        if ($module === null) {
            Response::notFound("Module n°$id introuvable");
            return;
        }
        Response::success("Module trouvé", $module);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['scolarite_id']) || empty($data['intitule']) || empty($data['niveau'])) {
            Response::error("Champs obligatoires manquants : scolarite_id, intitule, niveau", 422);
            return;
        }

        try {
            $newId = $this->model->create($data);
            $module = $this->model->findById($newId);
            Response::success("Module créé", $module, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Module n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['scolarite_id']) || empty($data['intitule']) || empty($data['niveau'])) {
            Response::error("Champs obligatoires manquants : scolarite_id, intitule, niveau", 422);
            return;
        }

        try {
            $this->model->update($id, $data);
            $module = $this->model->findById($id);
            Response::success("Module mis à jour", $module);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Module n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Module n°$id supprimé");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
