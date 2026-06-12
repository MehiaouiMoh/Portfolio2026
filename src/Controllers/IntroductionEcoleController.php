<?php
require_once __DIR__ . '/../Models/IntroductionEcoleModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class IntroductionEcoleController
{
    private IntroductionEcoleModel $model;

    public function __construct()
    {
        $this->model = new IntroductionEcoleModel();
    }

    public function index(Request $request): void
    {
        $intros = $this->model->findAll();
        Response::success("Liste des introductions d'école", $intros);
    }

    public function show(Request $request, $id): void
    {
        $intro = $this->model->findById((int) $id);
        if ($intro === null) {
            Response::notFound("Introduction école n°$id introuvable");
            return;
        }
        Response::success("Introduction école trouvée", $intro);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['scolarite_id']) || empty($data['titre_court']) || empty($data['nb_annees'])) {
            Response::error("Champs obligatoires manquants : scolarite_id, titre_court, nb_annees", 422);
            return;
        }

        try {
            $newId = $this->model->create($data);
            $intro = $this->model->findById($newId);
            Response::success("Introduction école créée", $intro, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Introduction école n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['scolarite_id']) || empty($data['titre_court']) || empty($data['nb_annees'])) {
            Response::error("Champs obligatoires manquants : scolarite_id, titre_court, nb_annees", 422);
            return;
        }

        try {
            $this->model->update($id, $data);
            $intro = $this->model->findById($id);
            Response::success("Introduction école mise à jour", $intro);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Introduction école n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Introduction école n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
