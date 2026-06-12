<?php
require_once __DIR__ . '/../Models/CarriereViseeModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class CarriereViseeController
{
    private CarriereViseeModel $model;

    public function __construct()
    {
        $this->model = new CarriereViseeModel();
    }

    public function index(Request $request): void
    {
        $carrieres = $this->model->findAll();
        Response::success("Liste des carrières visées", $carrieres);
    }

    public function show(Request $request, $id): void
    {
        $carriere = $this->model->findById((int) $id);
        if ($carriere === null) {
            Response::notFound("Carrière visée n°$id introuvable");
            return;
        }
        Response::success("Carrière visée trouvée", $carriere);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['intitule']) || empty($data['description'])) {
            Response::error("Champs obligatoires manquants : intitule, description", 422);
            return;
        }

        // user_id en dur pour l'instant (auth plus tard)
        $data['user_id'] = 1;

        try {
            $newId = $this->model->create($data);
            $carriere = $this->model->findById($newId);
            Response::success("Carrière visée créée", $carriere, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Carrière visée n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['intitule']) || empty($data['description'])) {
            Response::error("Champs obligatoires manquants : intitule, description", 422);
            return;
        }

        $data['user_id'] = 1;

        try {
            $this->model->update($id, $data);
            $carriere = $this->model->findById($id);
            Response::success("Carrière visée mise à jour", $carriere);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Carrière visée n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Carrière visée n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
