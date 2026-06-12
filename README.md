# Portfolio API

API REST backend d'un portfolio personnel, construite en PHP natif sans framework, avec une architecture MVC maison et une base de données SQLite.

> Projet en cours de développement — ce README est mis à jour au fil de l'avancement.

---

## Stack technique

| Couche | Technologie |
|---|---|
| Langage | PHP 8.x |
| Base de données | SQLite 3 |
| Authentification | JWT (`firebase/php-jwt`) |
| Tests | PHPUnit (structure en place) |
| Client API | Postman (collection incluse) |
| Frontend | html, css(imbriqué), javascript (pour appel api et remplissage dynamique), partage du jwt en CookieHttpOnly si possible pour eviter le localStorage |

---

## Architecture

Le projet suit un pattern MVC custom, sans framework externe.

```
portfolio/
├── api/
│   ├── index.php        ← point d'entrée unique (front controller)
│   └── routes.php       ← déclaration de toutes les routes
├── src/
│   ├── Core/            ← moteur de l'application
│   │   ├── Router.php   ← routeur (GET/POST/PUT/DELETE + segments dynamiques)
│   │   ├── Request.php  ← encapsulation de la requête HTTP
│   │   ├── Response.php ← réponses JSON standardisées
│   │   └── Database.php ← singleton PDO / SQLite
│   ├── Controllers/     ← un controller par ressource + dossier Authentification/
│   ├── Models/          ← un model par table (requêtes SQL)
│   └── Utils/           ← helpers (JWT, Auth)
├── database/
│   ├── schema.sql       ← création des tables
│   ├── seed.sql         ← données de test
│   ├── migrations/      ← historique des migrations
│   └── portfolio.sqlite ← base de données (générée)
├── tests/               ← tests unitaires (structure en place)
├── postman_collection.json
└── composer.json
```

---

## Installation

```bash
# 1. Cloner le dépôt
git clone <url-du-repo>
cd portfolio

# 2. Installer les dépendances
composer install

# 3. Créer la base de données et insérer les données de test
sqlite3 database/portfolio.sqlite < database/schema.sql
sqlite3 database/portfolio.sqlite < database/seed.sql

# 4. Lancer le serveur de développement PHP
php -S localhost:8000 -t api/
```

L'API est accessible sur `http://localhost:8000`.

---

## Base de données

### Modèle relationnel

```
users
 ├── informations          (1-1)
 ├── competences           (1-N) → categorie_competences
 ├── scolarite             (1-N)
 │    └── module           (1-N) ──┐
 ├── projet                (1-N)   │
 │    └── image_projet     (1-N)   │
 ├── carriere_visee        (1-N)   │
 └── experience_pro        (1-N)   │
                                   │
categorie_competences              │
langages                           │
                                   │
Tables de liaison :                │
  module_competence    ← module ───┘ × competences
  projet_langage       ← projet      × langages
  carriere_competence  ← carriere    × competences

visites  (table indépendante — tracking anonymisé)
```

### Tables

| Table | Description |
|---|---|
| `users` | Compte administrateur du portfolio |
| `informations` | Nom, prénom, téléphone, description, avatar |
| `competences` | Compétences par catégorie avec pourcentage |
| `categorie_competences` | Frontend, Backend, BDD, DevOps… |
| `langages` | Langages de programmation avec icône |
| `scolarite` | Formations (BTS, BUT…) |
| `module` | Modules d'une formation |
| `projet` | Projets réalisés |
| `image_projet` | Galerie d'images d'un projet |
| `experience_pro` | Expériences professionnelles |
| `carriere_visee` | Objectif de carrière |
| `visites` | Tracking anonymisé des visites (IP hashée) |
| `module_competence` | Liaison module ↔ compétence |
| `projet_langage` | Liaison projet ↔ langage |
| `carriere_competence` | Liaison carrière ↔ compétence |

---

## Authentification

L'API utilise **JWT** (JSON Web Token).

```
POST /api/auth/login
Body : { "email": "...", "password": "..." }
→ Retourne un token JWT

GET /api/auth/me
Header : Authorization: Bearer <token>
→ Retourne les infos de l'utilisateur connecté
```

Les routes d'écriture (`POST`, `PUT`, `DELETE`) sont protégées : elles vérifient le token dans le header `Authorization`.

---

## Endpoints API

### Publics (lecture seule)

| Méthode | Route | Description |
|---|---|---|
| `GET` | `/api/informations` | Infos du portfolio |
| `GET` | `/api/competences` | Liste des compétences |
| `GET` | `/api/categorie-competences` | Catégories de compétences |
| `GET` | `/api/langages` | Langages maîtrisés |
| `GET` | `/api/scolarite` | Parcours scolaire |
| `GET` | `/api/projets` | Projets réalisés |
| `GET` | `/api/experiences` | Expériences professionnelles |
| `GET` | `/api/carrieres` | Carrière visée |
| `GET` | `/api/modules` | Modules de formation |
| `GET` | `/api/images-projet` | Images des projets |
| `GET` | `/api/module-competence` | Liaisons module/compétence |
| `GET` | `/api/projet-langage` | Liaisons projet/langage |
| `GET` | `/api/carriere-competence` | Liaisons carrière/compétence |
| `POST` | `/api/visites` | Enregistrer une visite |

Chaque ressource expose aussi `GET /api/<ressource>/{id}` pour récupérer un élément par son ID.

### Protégés (JWT requis)

Toutes les ressources ci-dessus exposent en plus :

| Méthode | Route | Description |
|---|---|---|
| `POST` | `/api/<ressource>` | Créer |
| `PUT` | `/api/<ressource>/{id}` | Modifier |
| `DELETE` | `/api/<ressource>/{id}` | Supprimer |

---

## Tester l'API

Une collection Postman est disponible à la racine : `postman_collection.json`.

Importer le fichier dans Postman, créer une variable `baseUrl = http://localhost:8000`, puis lancer les requêtes.

---

## Roadmap

- [x] Architecture MVC custom (Router, Request, Response, Database)
- [x] Base de données SQLite + schéma complet (15 tables)
- [x] Authentification JWT
- [x] CRUD complet sur toutes les ressources
- [x] Routes publiques / protégées
- [x] Données de test (seed)
- [x] Collection Postman
- [ ] Remplir la base de données avec les vraies informations
- [ ] Tests unitaires (PHPUnit)
- [ ] Frontend React / Vue (à connecter via fetch)
- [ ] Déploiement
