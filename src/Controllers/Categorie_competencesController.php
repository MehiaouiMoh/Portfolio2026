<?php
require_once __DIR__ . '/../Models/Categorie_competencesModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class Categorie_competencesController
{
    private Categorie_competencesModel $model;

    public function __construct()
    {
        $this->model = new Categorie_competencesModel();
    }

    public function index(Request $request): void
    {
        $categories = $this->model->findAll();
        Response::success("Liste des catégories de compétences", $categories);
    }

    public function show(Request $request, $id): void
    {
        $categorie = $this->model->findById((int) $id);
        if ($categorie === null) {
            Response::notFound("Catégorie de compétences n°$id introuvable");
            return;
        }
        Response::success("Catégorie de compétences trouvée", $categorie);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['name'])) {
            Response::error("Champs obligatoires manquants : name", 422);
            return;
        }

        try {
            $newId = $this->model->create($data);
            $categorie = $this->model->findById($newId);
            Response::success("Catégorie de compétences créée", $categorie, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Catégorie de compétences n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['name'])) {
            Response::error("Champs obligatoires manquants : name", 422);
            return;
        }

        try {
            $this->model->update($id, $data);
            $categorie = $this->model->findById($id);
            Response::success("Catégorie de compétences mise à jour", $categorie);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Catégorie de compétences n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Catégorie de compétences n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
