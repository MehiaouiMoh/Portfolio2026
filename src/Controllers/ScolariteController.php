<?php
require_once __DIR__ . '/../Models/ScolariteModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class ScolariteController
{
    private ScolariteModel $model;

    public function __construct()
    {
        $this->model = new ScolariteModel();
    }

    public function index(Request $request): void
    {
        $scolarites = $this->model->findAll();
        Response::success("Liste des scolarités", $scolarites);
    }

    public function show(Request $request, $id): void
    {
        $scolarite = $this->model->findById((int) $id);
        if ($scolarite === null) {
            Response::notFound("Scolarité n°$id introuvable");
            return;
        }
        Response::success("Scolarité trouvée", $scolarite);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['intitule']) || empty($data['niveau']) || empty($data['etablissement']) || empty($data['ville']) || empty($data['date_debut'])) {
            Response::error("Champs obligatoires manquants : intitule, niveau, etablissement, ville, date_debut", 422);
            return;
        }

        // user_id en dur pour l'instant (auth plus tard)
        $data['user_id'] = 1;

        try {
            $newId = $this->model->create($data);
            $scolarite = $this->model->findById($newId);
            Response::success("Scolarité créée", $scolarite, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Scolarité n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['intitule']) || empty($data['niveau']) || empty($data['etablissement']) || empty($data['ville']) || empty($data['date_debut'])) {
            Response::error("Champs obligatoires manquants : intitule, niveau, etablissement, ville, date_debut", 422);
            return;
        }

        $data['user_id'] = 1;

        try {
            $this->model->update($id, $data);
            $scolarite = $this->model->findById($id);
            Response::success("Scolarité mise à jour", $scolarite);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Scolarité n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Scolarité n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
