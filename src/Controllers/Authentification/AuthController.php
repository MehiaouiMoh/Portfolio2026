<?php
require_once __DIR__ . '/../../Models/UsersModel.php';
require_once __DIR__ . '/../../Core/Request.php';
require_once __DIR__ . '/../../Core/Response.php';
require_once __DIR__ . '/../../Utils/jwtHelper.php';
require_once __DIR__ . '/../../Utils/AuthHelper.php';

class AuthController
{
    private UsersModel $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    /**
     * Connexion — POST /api/auth/login
     *
     * Vérifie les identifiants, génère un JWT, puis :
     *   - Pose un cookie HttpOnly "jwt_token" pour le navigateur (sécurisé, anti-XSS)
     *   - Retourne aussi les infos user en JSON (compatible Postman / clients API)
     *
     * Le cookie est SameSite=Strict pour se protéger du CSRF.
     * Il est Secure uniquement si la connexion est en HTTPS (production).
     */
    public function login(Request $request): void
    {
        $data = $request->getBody();

        if (empty($data['email']) || empty($data['password'])) {
            Response::error("Champs obligatoires manquants : email, password", 422);
            return;
        }

        $email    = trim(htmlspecialchars($data['email']));
        $password = $data['password'];

        $user = $this->usersModel->findByEmail($email);
        if ($user === null || !password_verify($password, $user['password'])) {
            Response::error("Identifiants incorrects", 401);
            return;
        }

        // Génération du JWT (TTL : 1 heure)
        $token = JwtHelper::generate([
            'user_id' => $user['id'],
            'email'   => $user['email']
        ]);

        // Pose du cookie HttpOnly — le navigateur l'enverra automatiquement
        // à chaque requête vers le même domaine sans que le JS puisse le lire.
        $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        setcookie('jwt_token', $token, [
            'expires'  => time() + 3600, // durée identique au TTL du JWT
            'path'     => '/',
            'httponly' => true,           // inaccessible à document.cookie
            'samesite' => 'Strict',       // pas de transmission cross-site (anti-CSRF)
            'secure'   => $isHttps,       // HTTPS seulement en production
        ]);

        Response::success("Connexion réussie", [
            'user' => [
                'id'       => $user['id'],
                'username' => $user['username'],
                'email'    => $user['email']
            ]
        ]);
    }

    /**
     * Déconnexion — POST /api/auth/logout
     *
     * Expire le cookie côté serveur en lui assignant une date passée.
     * Le navigateur le supprime dès réception de la réponse.
     */
    public function logout(): void
    {
        $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
        setcookie('jwt_token', '', [
            'expires'  => time() - 3600, // date dans le passé = suppression immédiate
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Strict',
            'secure'   => $isHttps,
        ]);

        Response::success("Déconnexion réussie");
    }

    /**
     * Infos de l'utilisateur connecté — GET /api/auth/me
     *
     * Délègue la vérification du token à AuthHelper (cookie ou Bearer).
     * Utilisé par le frontend pour vérifier la session et récupérer le profil.
     */
    public function me(Request $request): void
    {
        $payload = AuthHelper::requireAuth($request);

        $user = $this->usersModel->findById($payload['user_id']);
        if ($user === null) {
            Response::unauthorized("Utilisateur introuvable");
            return;
        }

        unset($user['password']); // ne jamais renvoyer le hash du mot de passe
        Response::success("Utilisateur connecté", $user);
    }
}
