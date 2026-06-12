<?php
//Definir la classe Response pour gérer les réponses HTTP

class Response
{
    //Response json
    public static function json($data, int $statusCode = 200): void
    {
        // Definir le bon Content-Type
        header('Content-Type: application/json');
        // Definir le code de statut HTTP
        http_response_code($statusCode);
        // Encoder les données en JSON et les envoyer
        echo json_encode($data);
        // Terminer le script pour éviter d'envoyer du contenu supplémentaire
        exit();
    }

    //Reponse success
    public static function success(string $message, $data=null, int $statusCode = 200): void
    {
        // Construire un array
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
        // faire un self::json pour envoyer la réponse
        self::json($response, $statusCode);
    }

    //Reponse error
    public static function error(string $message, int $statusCode = 400): void
    {
        // Construire un array
        $response = [
            'success' => false,
            'message' => $message
        ];
        // faire un self::json pour envoyer la réponse
        self::json($response, $statusCode);
    }

    //Reponse not found
    public static function notFound(string $message = 'Ressource non trouvée'): void
    {
        self::error($message, 404);
    }

    //Reponse unauthorized
    public static function unauthorized(string $message = 'Accès non autorisé'): void
    {
        self::error($message, 401);
    }
}
