<?php
require_once __DIR__ . '/../Models/InformationsModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';

class InformationsController
{
    private InformationsModel $model;
    
    public function __construct()
    {
        $this->model = new InformationsModel();
    }
    
    public function index(Request $request): void
    {
        $informations = $this->model->findAll();
        Response::success("Liste des informations", $informations);
    }
    
    public function show(Request $request, $id): void
    {
        $info = $this->model->findById((int) $id);
        if ($info === null) {
            Response::notFound("Information n°$id introuvable");
            return;
        }
        Response::success("Information trouvée", $info);
    }
    
    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['nom']) || empty($data['prenom']) || empty($data['description'])) {
            Response::error("Champs obligatoires manquants : nom, prenom, description", 422);
            return;
        }
        
        // user_id en dur pour l'instant (auth plus tard)
        $data['user_id'] = 1;
        
        try {
            $newId = $this->model->create($data);
            $info = $this->model->findById($newId);
            Response::success("Information créée", $info, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
    
    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Information n°$id introuvable");
            return;
        }
        
        $data = $request->getBody();
        
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['description'])) {
            Response::error("Champs obligatoires manquants", 422);
            return;
        }
        
        $data['user_id'] = 1;
        
        try {
            $this->model->update($id, $data);
            $info = $this->model->findById($id);
            Response::success("Information mise à jour", $info);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
    
    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Information n°$id introuvable");
            return;
        }
        
        try {
            $this->model->delete($id);
            Response::success("Information n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
