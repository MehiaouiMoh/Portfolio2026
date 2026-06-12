<?php
require_once __DIR__ . '/../Models/LangagesModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class LangagesController
{
    private LangagesModel $model;

    public function __construct()
    {
        $this->model = new LangagesModel();
    }

    public function index(Request $request): void
    {
        $langages = $this->model->findAll();
        Response::success("Liste des langages", $langages);
    }

    public function show(Request $request, $id): void
    {
        $langage = $this->model->findById((int) $id);
        if ($langage === null) {
            Response::notFound("Langage n°$id introuvable");
            return;
        }
        Response::success("Langage trouvé", $langage);
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
            $langage = $this->model->findById($newId);
            Response::success("Langage créé", $langage, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Langage n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['name']) || empty($data['categorie_id'])) {
            Response::error("Champs obligatoires manquants : name, categorie_id", 422);
            return;
        }

        try {
            $this->model->update($id, $data);
            $langage = $this->model->findById($id);
            Response::success("Langage mis à jour", $langage);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Langage n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Langage n°$id supprimé");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
