<?php

class UploadImgHelper {
    private const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_SIZE = 2 * 1024 * 1024; // 2MB
    private const UPLOAD_BASE = __DIR__ . '/../../assets/uploads/';

    /**
     * Upload une image dans le dossier assets/uploads/{folder}/
     * Retourne le chemin relatif à stocker en BDD.
     * Lance une Exception en cas d'erreur.
    */

    public static function upload(array $file, string $folder): string {
        // Validation centralisée ici, une seule fois

        self::validate($file);
 
        $uploadDir = self::UPLOAD_BASE . $folder . '/';
 
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_', true) . '.' . strtolower($extension);
        $destination = $uploadDir . $filename;
 
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Impossible de déplacer le fichier uploadé.");
        }
 
        return 'assets/uploads/' . $folder . '/' . $filename;
    }

    /**
     * Valide le fichier uploadé (présence, taille, type MIME).
     * Lance une Exception si invalide.
     */

    private static function validate(array $file): void {
        if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Aucun fichier uploadé ou une erreur est survenue.");
        }

        if ($file['size'] > self::MAX_SIZE) {
            throw new Exception("Le fichier dépasse la taille maximale de 2MB.");
        }

        if (!in_array(mime_content_type($file['tmp_name']), self::ALLOWED_TYPES)) {
            throw new Exception("Type de fichier non autorisé. Seuls les JPEG, PNG et WEBP sont acceptés.");
        }
    }
}