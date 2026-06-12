<?php
require_once __DIR__ . '/../Models/ImageProjetModel.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';
require_once __DIR__ . '/../Utils/AuthHelper.php';
require_once __DIR__ . '/../Utils/UploadImgHelper.php';

class ImageProjetController
{
    private ImageProjetModel $model;

    public function __construct()
    {
        $this->model = new ImageProjetModel();
    }

    public function index(Request $request): void
    {
        $images = $this->model->findAll();
        Response::success("Liste des images de projet", $images);
    }

    public function show(Request $request, $id): void
    {
        $image = $this->model->findById((int) $id);
        if ($image === null) {
            Response::notFound("Image de projet n°$id introuvable");
            return;
        }
        Response::success("Image de projet trouvée", $image);
    }

    public function store(Request $request): void
    {
        AuthHelper::requireAuth($request);
        $data = $request->getBody();

        if (empty($data['projet_id']) || !isset($data['ordre'])) {
            Response::error("Champs obligatoires manquants : projet_id, image_url, ordre", 422);
            return;
        }

        try {
            $data['image_url'] = UploadImgHelper::upload($_FILES['image'], 'projets');
            $newId = $this->model->create($data);
            $image = $this->model->findById($newId);
            Response::success("Image de projet créée", $image, 201);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Image de projet n°$id introuvable");
            return;
        }

        $data = $request->getBody();

        if (empty($data['projet_id']) || !isset($data['ordre'])) {
            Response::error("Champs obligatoires manquants : projet_id, image_url, ordre", 422);
            return;
        }

        try {
            // On remplace l'image seulement si un fichier est envoyé
            if (!empty($_FILES['image']['tmp_name'])) {
                $data['image_url'] = UploadImgHelper::upload($_FILES['image'], 'projets');
            }
            
            $this->model->update($id, $data);
            $image = $this->model->findById($id);
            Response::success("Image de projet mise à jour", $image);
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, $id): void
    {
        AuthHelper::requireAuth($request);
        $id = (int) $id;

        if ($this->model->findById($id) === null) {
            Response::notFound("Image de projet n°$id introuvable");
            return;
        }

        try {
            $this->model->delete($id);
            Response::success("Image de projet n°$id supprimée");
        } catch (PDOException $e) {
            Response::error("Erreur : " . $e->getMessage(), 500);
        }
    }
}
