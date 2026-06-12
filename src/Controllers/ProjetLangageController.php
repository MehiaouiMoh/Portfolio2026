<?php
require_once __DIR__ . '/../Models/ProjetLangageModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class ProjetLangageController
{
    private ProjetLangageModel $model;

    public function __construct()
    {
        $this->model = new ProjetLangageModel();
    }

    public function index(Request $request): void
    {
        $projetLangages = $this->model->findAll();
        Response::success("Liste des liaisons projet/langage", $projetLangages);
    }

    public function show(Request $request, $id): void
    {
        $projetLangage = $this->model->findById((int) $id);
        if ($projetLangage === null) {
            Response::notFound("Liaison projet/langage n°$id introuvable");
            return;
        }
        Response::success("Liaison projet/langage trouvée", $projetLangage);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['projet_id']) || empty($data['langage_id'])) {
            Response::error("Champs obligatoires manquants : projet_id, langage_id", 422);
            return;
        }

        try {
            $newId = $this->model->create($data);
            $projetLangage = $this->model->findById($newId);
            Response::success("Liaison projet/langage créée", $projetLangage, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Liaison projet/langage n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Liaison projet/langage n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
