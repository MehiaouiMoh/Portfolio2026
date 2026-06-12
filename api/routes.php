<?php

// ============================================================
// Chargement des controllers
// ============================================================
require_once __DIR__ . '/../src/Controllers/Authentification/AuthController.php';
require_once __DIR__ . '/../src/Controllers/InformationsController.php';
require_once __DIR__ . '/../src/Controllers/Categorie_competencesController.php';
require_once __DIR__ . '/../src/Controllers/CompetencesController.php';
require_once __DIR__ . '/../src/Controllers/LangagesController.php';
require_once __DIR__ . '/../src/Controllers/FrameworksController.php';
require_once __DIR__ . '/../src/Controllers/LogicielsController.php';
require_once __DIR__ . '/../src/Controllers/VisitesController.php';
require_once __DIR__ . '/../src/Controllers/ScolariteController.php';
require_once __DIR__ . '/../src/Controllers/ProjetsController.php';
require_once __DIR__ . '/../src/Controllers/CarriereViseeController.php';
require_once __DIR__ . '/../src/Controllers/ExperienceProController.php';
require_once __DIR__ . '/../src/Controllers/ModuleController.php';
require_once __DIR__ . '/../src/Controllers/ImageProjetController.php';
require_once __DIR__ . '/../src/Controllers/EnseignementController.php';
require_once __DIR__ . '/../src/Controllers/ProjetLangageController.php';
require_once __DIR__ . '/../src/Controllers/CarriereCompetenceController.php';
require_once __DIR__ . '/../src/Controllers/IntroductionEcoleController.php';

// ============================================================
// Routes publiques
// ============================================================

$router->post('/api/auth/login',  [AuthController::class, 'login']);
$router->post('/api/auth/logout', [AuthController::class, 'logout']);

$router->get('/api/informations',      [InformationsController::class, 'index']);
$router->get('/api/informations/{id}', [InformationsController::class, 'show']);

$router->get('/api/categorie-competences',      [Categorie_competencesController::class, 'index']);
$router->get('/api/categorie-competences/{id}', [Categorie_competencesController::class, 'show']);

$router->get('/api/competences',      [CompetencesController::class, 'index']);
$router->get('/api/competences/{id}', [CompetencesController::class, 'show']);

$router->get('/api/langages',      [LangagesController::class, 'index']);
$router->get('/api/langages/{id}', [LangagesController::class, 'show']);

$router->get('/api/frameworks',      [FrameworksController::class, 'index']);
$router->get('/api/frameworks/{id}', [FrameworksController::class, 'show']);

$router->get('/api/logiciels',      [LogicielsController::class, 'index']);
$router->get('/api/logiciels/{id}', [LogicielsController::class, 'show']);

$router->get('/api/scolarite',      [ScolariteController::class, 'index']);
$router->get('/api/scolarite/{id}', [ScolariteController::class, 'show']);

$router->get('/api/projets',      [ProjetsController::class, 'index']);
$router->get('/api/projets/{id}', [ProjetsController::class, 'show']);

$router->get('/api/carrieres',      [CarriereViseeController::class, 'index']);
$router->get('/api/carrieres/{id}', [CarriereViseeController::class, 'show']);

$router->get('/api/experiences',      [ExperienceProController::class, 'index']);
$router->get('/api/experiences/{id}', [ExperienceProController::class, 'show']);

$router->get('/api/modules',      [ModuleController::class, 'index']);
$router->get('/api/modules/{id}', [ModuleController::class, 'show']);

$router->get('/api/images-projet',      [ImageProjetController::class, 'index']);
$router->get('/api/images-projet/{id}', [ImageProjetController::class, 'show']);

$router->get('/api/enseignements',             [EnseignementController::class, 'index']);
$router->get('/api/enseignements/{id}',        [EnseignementController::class, 'show']);
$router->get('/api/modules/{id}/enseignements', [EnseignementController::class, 'byModule']);

$router->get('/api/projet-langage',      [ProjetLangageController::class, 'index']);
$router->get('/api/projet-langage/{id}', [ProjetLangageController::class, 'show']);

$router->get('/api/carriere-competence',      [CarriereCompetenceController::class, 'index']);
$router->get('/api/carriere-competence/{id}', [CarriereCompetenceController::class, 'show']);

$router->get('/api/introduction-ecole',      [IntroductionEcoleController::class, 'index']);
$router->get('/api/introduction-ecole/{id}', [IntroductionEcoleController::class, 'show']);

$router->post('/api/visites', [VisitesController::class, 'store']);

// ============================================================
// Routes protégées (auth vérifiée dans chaque controller)
// ============================================================

$router->get('/api/auth/me', [AuthController::class, 'me']);

$router->post('/api/informations',        [InformationsController::class, 'store']);
$router->put('/api/informations/{id}',    [InformationsController::class, 'update']);
$router->delete('/api/informations/{id}', [InformationsController::class, 'destroy']);

$router->post('/api/categorie-competences',        [Categorie_competencesController::class, 'store']);
$router->put('/api/categorie-competences/{id}',    [Categorie_competencesController::class, 'update']);
$router->delete('/api/categorie-competences/{id}', [Categorie_competencesController::class, 'destroy']);

$router->post('/api/competences',        [CompetencesController::class, 'store']);
$router->put('/api/competences/{id}',    [CompetencesController::class, 'update']);
$router->delete('/api/competences/{id}', [CompetencesController::class, 'destroy']);

$router->post('/api/langages',        [LangagesController::class, 'store']);
$router->put('/api/langages/{id}',    [LangagesController::class, 'update']);
$router->delete('/api/langages/{id}', [LangagesController::class, 'destroy']);

$router->post('/api/frameworks',        [FrameworksController::class, 'store']);
$router->put('/api/frameworks/{id}',    [FrameworksController::class, 'update']);
$router->delete('/api/frameworks/{id}', [FrameworksController::class, 'destroy']);

$router->post('/api/logiciels',        [LogicielsController::class, 'store']);
$router->put('/api/logiciels/{id}',    [LogicielsController::class, 'update']);
$router->delete('/api/logiciels/{id}', [LogicielsController::class, 'destroy']);

$router->get('/api/visites',         [VisitesController::class, 'index']);
$router->get('/api/visites/{id}',    [VisitesController::class, 'show']);
$router->delete('/api/visites/{id}', [VisitesController::class, 'destroy']);

$router->post('/api/scolarite',        [ScolariteController::class, 'store']);
$router->put('/api/scolarite/{id}',    [ScolariteController::class, 'update']);
$router->delete('/api/scolarite/{id}', [ScolariteController::class, 'destroy']);

$router->post('/api/projets',        [ProjetsController::class, 'store']);
$router->put('/api/projets/{id}',    [ProjetsController::class, 'update']);
$router->delete('/api/projets/{id}', [ProjetsController::class, 'destroy']);

$router->post('/api/carrieres',        [CarriereViseeController::class, 'store']);
$router->put('/api/carrieres/{id}',    [CarriereViseeController::class, 'update']);
$router->delete('/api/carrieres/{id}', [CarriereViseeController::class, 'destroy']);

$router->post('/api/experiences',        [ExperienceProController::class, 'store']);
$router->put('/api/experiences/{id}',    [ExperienceProController::class, 'update']);
$router->delete('/api/experiences/{id}', [ExperienceProController::class, 'destroy']);

$router->post('/api/modules',        [ModuleController::class, 'store']);
$router->put('/api/modules/{id}',    [ModuleController::class, 'update']);
$router->delete('/api/modules/{id}', [ModuleController::class, 'destroy']);

$router->post('/api/images-projet',        [ImageProjetController::class, 'store']);
$router->put('/api/images-projet/{id}',    [ImageProjetController::class, 'update']);
$router->delete('/api/images-projet/{id}', [ImageProjetController::class, 'destroy']);

$router->post('/api/enseignements',        [EnseignementController::class, 'store']);
$router->put('/api/enseignements/{id}',    [EnseignementController::class, 'update']);
$router->delete('/api/enseignements/{id}', [EnseignementController::class, 'destroy']);

$router->post('/api/projet-langage',        [ProjetLangageController::class, 'store']);
$router->delete('/api/projet-langage/{id}', [ProjetLangageController::class, 'destroy']);

$router->post('/api/carriere-competence',        [CarriereCompetenceController::class, 'store']);
$router->delete('/api/carriere-competence/{id}', [CarriereCompetenceController::class, 'destroy']);

$router->post('/api/introduction-ecole',        [IntroductionEcoleController::class, 'store']);
$router->put('/api/introduction-ecole/{id}',    [IntroductionEcoleController::class, 'update']);
$router->delete('/api/introduction-ecole/{id}', [IntroductionEcoleController::class, 'destroy']);
