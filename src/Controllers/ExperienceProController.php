<?php
require_once __DIR__ . '/../Models/ExperienceProModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class ExperienceProController
{
    private ExperienceProModel $model;

    public function __construct()
    {
        $this->model = new ExperienceProModel();
    }

    public function index(Request $request): void
    {
        $experiences = $this->model->findAll();
        Response::success("Liste des expériences professionnelles", $experiences);
    }

    public function show(Request $request, $id): void
    {
        $experience = $this->model->findById((int) $id);
        if ($experience === null) {
            Response::notFound("Expérience professionnelle n°$id introuvable");
            return;
        }
        Response::success("Expérience professionnelle trouvée", $experience);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['intitule']) || empty($data['entreprise']) || empty($data['ville']) || empty($data['date_debut'])) {
            Response::error("Champs obligatoires manquants : intitule, entreprise, ville, date_debut", 422);
            return;
        }

        // user_id en dur pour l'instant (auth plus tard)
        $data['user_id'] = 1;

        try {
            $newId = $this->model->create($data);
            $experience = $this->model->findById($newId);
            Response::success("Expérience professionnelle créée", $experience, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Expérience professionnelle n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['intitule']) || empty($data['entreprise']) || empty($data['ville']) || empty($data['date_debut'])) {
            Response::error("Champs obligatoires manquants : intitule, entreprise, ville, date_debut", 422);
            return;
        }

        $data['user_id'] = 1;

        try {
            $this->model->update($id, $data);
            $experience = $this->model->findById($id);
            Response::success("Expérience professionnelle mise à jour", $experience);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Expérience professionnelle n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Expérience professionnelle n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
