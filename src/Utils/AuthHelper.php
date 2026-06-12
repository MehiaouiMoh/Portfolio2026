<?php

require_once __DIR__ . '/JwtHelper.php';
require_once __DIR__ . '/../Core/Request.php';
require_once __DIR__ . '/../Core/Response.php';

class AuthHelper
{
    /**
     * Vérifie qu'une requête contient un JWT valide.
     *
     * Ordre de priorité pour trouver le token :
     *   1. Cookie HttpOnly "jwt_token" — posé par PHP à la connexion,
     *      envoyé automatiquement par le navigateur, inaccessible au JS (anti-XSS).
     *   2. Header "Authorization: Bearer <token>" — pour Postman, curl, ou tout
     *      client API qui ne gère pas les cookies.
     *
     * Retourne le payload JWT décodé si valide.
     * Envoie une 401 et stoppe l'exécution si absent ou invalide.
     */
    public static function requireAuth(Request $request): array
    {
        // --- Priorité 1 : cookie HttpOnly (navigateur) ---
        $token = $_COOKIE['jwt_token'] ?? null;

        // --- Priorité 2 : header Authorization (clients API) ---
        if (empty($token)) {
            $authHeader = $request->getHeader('Authorization');
            if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
                $token = substr($authHeader, 7); // supprime le préfixe "Bearer "
            }
        }

        if (empty($token)) {
            Response::unauthorized("Token manquant — connectez-vous d'abord");
            exit;
        }

        $payload = JwtHelper::verify($token);

        if ($payload === null) {
            Response::unauthorized("Token invalide ou expiré — reconnectez-vous");
            exit;
        }

        return $payload;
    }
}
