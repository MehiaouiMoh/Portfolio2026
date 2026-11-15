# Routes de l'API — Portfolio2026 (PHP pur)

Source : `api/routes.php` (branche `develop`).

## Conventions

- Toutes les réponses : `{ success, message, data }`
- Erreurs : `{ success: false, message }` avec code 401, 404, 422 ou 500
- **Liste** = `{ success, message, data: [ {...} ] }`
- **Objet** = `{ success, message, data: {...} }` (404 si introuvable)
- **Créé** = Objet avec code HTTP 201
- **Supprimé** = `{ success, message, data: null }`
- Routes protégées : JWT dans le cookie HttpOnly `jwt_token` ou l'en-tête `Authorization: Bearer <token>`

## Tableau des routes

| Méthode | URL | Protégée (JWT) ? | Controller::méthode | Paramètres attendus | Forme de la réponse |
|---|---|---|---|---|---|
| **Auth** | | | | | |
| POST | /api/auth/login | non | AuthController::login | email, password | Objet `data: { user: {id, username, email} }` + cookie `jwt_token` |
| POST | /api/auth/logout | non | AuthController::logout | — | `{ success, message, data: null }` (cookie expiré) |
| GET | /api/auth/me | oui | AuthController::me | — | Objet : l'utilisateur, sans `password` |
| **Informations** | | | | | |
| GET | /api/informations | non | InformationsController::index | — | Liste |
| GET | /api/informations/{id} | non | InformationsController::show | id | Objet |
| POST | /api/informations | oui | InformationsController::store | nom, prenom, description | Créé |
| PUT | /api/informations/{id} | oui | InformationsController::update | id + champs | Objet |
| DELETE | /api/informations/{id} | oui | InformationsController::destroy | id | Supprimé |
| **Catégories de compétences** | | | | | |
| GET | /api/categorie-competences | non | Categorie_competencesController::index | — | Liste |
| GET | /api/categorie-competences/{id} | non | Categorie_competencesController::show | id | Objet |
| POST | /api/categorie-competences | oui | Categorie_competencesController::store | name | Créé |
| PUT | /api/categorie-competences/{id} | oui | Categorie_competencesController::update | id, name | Objet |
| DELETE | /api/categorie-competences/{id} | oui | Categorie_competencesController::destroy | id | Supprimé |
| **Compétences** | | | | | |
| GET | /api/competences | non | CompetencesController::index | — | Liste |
| GET | /api/competences/{id} | non | CompetencesController::show | id | Objet |
| POST | /api/competences | oui | CompetencesController::store | categorie_id, nom | Créé |
| PUT | /api/competences/{id} | oui | CompetencesController::update | id, categorie_id | Objet |
| DELETE | /api/competences/{id} | oui | CompetencesController::destroy | id | Supprimé |
| **Langages** | | | | | |
| GET | /api/langages | non | LangagesController::index | — | Liste |
| GET | /api/langages/{id} | non | LangagesController::show | id | Objet |
| POST | /api/langages | oui | LangagesController::store | name, categorie_id | Créé |
| PUT | /api/langages/{id} | oui | LangagesController::update | id, name, categorie_id | Objet |
| DELETE | /api/langages/{id} | oui | LangagesController::destroy | id | Supprimé |
| **Frameworks** | | | | | |
| GET | /api/frameworks | non | FrameworksController::index | — | Liste |
| GET | /api/frameworks/{id} | non | FrameworksController::show | id | Objet |
| POST | /api/frameworks | oui | FrameworksController::store | name, categorie_id | Créé |
| PUT | /api/frameworks/{id} | oui | FrameworksController::update | id, name, categorie_id | Objet |
| DELETE | /api/frameworks/{id} | oui | FrameworksController::destroy | id | Supprimé |
| **Logiciels** | | | | | |
| GET | /api/logiciels | non | LogicielsController::index | — | Liste |
| GET | /api/logiciels/{id} | non | LogicielsController::show | id | Objet |
| POST | /api/logiciels | oui | LogicielsController::store | name, categorie_id | Créé |
| PUT | /api/logiciels/{id} | oui | LogicielsController::update | id, name, categorie_id | Objet |
| DELETE | /api/logiciels/{id} | oui | LogicielsController::destroy | id | Supprimé |
| **Scolarité** | | | | | |
| GET | /api/scolarite | non | ScolariteController::index | — | Liste (chaque élément contient `modules_count`) |
| GET | /api/scolarite/{id} | non | ScolariteController::show | id | Objet |
| POST | /api/scolarite | oui | ScolariteController::store | intitule, niveau, etablissement, ville, date_debut | Créé |
| PUT | /api/scolarite/{id} | oui | ScolariteController::update | id + mêmes champs que POST | Objet |
| DELETE | /api/scolarite/{id} | oui | ScolariteController::destroy | id | Supprimé |
| **Introduction école** | | | | | |
| GET | /api/introduction-ecole | non | IntroductionEcoleController::index | — | Liste |
| GET | /api/introduction-ecole/{id} | non | IntroductionEcoleController::show | id | Objet |
| POST | /api/introduction-ecole | oui | IntroductionEcoleController::store | scolarite_id, titre_court, nb_annees | Créé |
| PUT | /api/introduction-ecole/{id} | oui | IntroductionEcoleController::update | id + mêmes champs que POST | Objet |
| DELETE | /api/introduction-ecole/{id} | oui | IntroductionEcoleController::destroy | id | Supprimé |
| **Modules** | | | | | |
| GET | /api/modules | non | ModuleController::index | — | Liste |
| GET | /api/modules/{id} | non | ModuleController::show | id | Objet |
| POST | /api/modules | oui | ModuleController::store | scolarite_id, intitule, niveau | Créé |
| PUT | /api/modules/{id} | oui | ModuleController::update | id + mêmes champs que POST | Objet |
| DELETE | /api/modules/{id} | oui | ModuleController::destroy | id | Supprimé |
| **Enseignements** | | | | | |
| GET | /api/enseignements | non | EnseignementController::index | — | Liste |
| GET | /api/enseignements/{id} | non | EnseignementController::show | id | Objet |
| GET | /api/modules/{id}/enseignements | non | EnseignementController::byModule | id (du module) | Liste |
| POST | /api/enseignements | oui | EnseignementController::store | module_id, nom | Créé |
| PUT | /api/enseignements/{id} | oui | EnseignementController::update | id, module_id, nom | Objet |
| DELETE | /api/enseignements/{id} | oui | EnseignementController::destroy | id | Supprimé |
| **Projets** | | | | | |
| GET | /api/projets | non | ProjetsController::index | — | Liste |
| GET | /api/projets/{id} | non | ProjetsController::show | id | Objet |
| POST | /api/projets | oui | ProjetsController::store | titre, description, date_debut | Créé |
| PUT | /api/projets/{id} | oui | ProjetsController::update | id + mêmes champs que POST | Objet |
| DELETE | /api/projets/{id} | oui | ProjetsController::destroy | id | Supprimé |
| **Images de projet** | | | | | |
| GET | /api/images-projet | non | ImageProjetController::index | — | Liste |
| GET | /api/images-projet/{id} | non | ImageProjetController::show | id | Objet |
| POST | /api/images-projet | oui | ImageProjetController::store | projet_id, image_url, ordre | Créé |
| PUT | /api/images-projet/{id} | oui | ImageProjetController::update | id + mêmes champs que POST | Objet |
| DELETE | /api/images-projet/{id} | oui | ImageProjetController::destroy | id | Supprimé |
| **Projet ↔ Langage** | | | | | |
| GET | /api/projet-langage | non | ProjetLangageController::index | — | Liste |
| GET | /api/projet-langage/{id} | non | ProjetLangageController::show | id | Objet |
| POST | /api/projet-langage | oui | ProjetLangageController::store | projet_id, langage_id | Créé |
| DELETE | /api/projet-langage/{id} | oui | ProjetLangageController::destroy | id | Supprimé |
| **Carrières visées** | | | | | |
| GET | /api/carrieres | non | CarriereViseeController::index | — | Liste |
| GET | /api/carrieres/{id} | non | CarriereViseeController::show | id | Objet |
| POST | /api/carrieres | oui | CarriereViseeController::store | intitule, description | Créé |
| PUT | /api/carrieres/{id} | oui | CarriereViseeController::update | id, intitule, description | Objet |
| DELETE | /api/carrieres/{id} | oui | CarriereViseeController::destroy | id | Supprimé |
| **Carrière ↔ Compétence** | | | | | |
| GET | /api/carriere-competence | non | CarriereCompetenceController::index | — | Liste |
| GET | /api/carriere-competence/{id} | non | CarriereCompetenceController::show | id | Objet |
| POST | /api/carriere-competence | oui | CarriereCompetenceController::store | carriere_id, competence_id | Créé |
| DELETE | /api/carriere-competence/{id} | oui | CarriereCompetenceController::destroy | id | Supprimé |
| **Expériences pro** | | | | | |
| GET | /api/experiences | non | ExperienceProController::index | — | Liste |
| GET | /api/experiences/{id} | non | ExperienceProController::show | id | Objet |
| POST | /api/experiences | oui | ExperienceProController::store | intitule, entreprise, ville, date_debut | Créé |
| PUT | /api/experiences/{id} | oui | ExperienceProController::update | id + mêmes champs que POST | Objet |
| DELETE | /api/experiences/{id} | oui | ExperienceProController::destroy | id | Supprimé |
| **Visites** | | | | | |
| POST | /api/visites | non | VisitesController::store | ip_address_hash, page | Créé |
| GET | /api/visites | oui | VisitesController::index | — | Liste |
| GET | /api/visites/{id} | oui | VisitesController::show | id | Objet |
| DELETE | /api/visites/{id} | oui | VisitesController::destroy | id | Supprimé |

## Remarque

`ModuleCompetenceController` existe (paramètres : module_id, competence_id) mais aucune route ne pointe vers lui dans `routes.php` : à ajouter ou supprimer pendant la migration.