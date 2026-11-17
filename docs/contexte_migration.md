# Contexte : migration Portfolio2026 (PHP pur → Laravel)

> Mis à jour le 17/11/2026, en fin de phase 1 et après les incidents du 16-17/11.
> Mode de travail : accompagnement pédagogique pas à pas. Mohamed exécute lui-même chaque étape, on explique le *pourquoi*, et on vérifie après chaque action.

## Projet
- Dépôt : github.com/MehiaouiMoh/Portfolio2026 (`main` = prod, `develop` = dev ; on fusionne `develop` dans `main` par Pull Request)
- Site : mehiaoui-lab.fr, chez o2switch (cPanel mutualisé : pas de `sudo`, pas d'`apt`), compte `sc13duga0395`
- Dev local : WSL Ubuntu, `~/ProjetsPersonnels/Portfolio2026`, `php -S 127.0.0.1:8000 router.php`
- Front : pages HTML statiques + JS vanilla qui appelle l'API via `apiFetch('/api/...')` (`assets/js/app.js`)
- Backoffice : `admin/login.html`, `admin/dashboard.html`

## Backend actuel (à migrer)
- PHP 8.4, MVC maison : `src/Core` (Router, Request, Response, Database PDO singleton, Logger), `src/Controllers`, `src/Models`, `src/Utils` (AuthHelper, jwtHelper, UploadImgHelper)
- Point d'entrée : `api/index.php` + `api/routes.php`, plus `api/.htaccess` (routage vers index.php)
- Composer : `firebase/php-jwt` (`vendor/` non versionné)
- Base : SQLite `database/portfolio.sqlite` (non versionnée), schéma dans `database/schema.sql`
- Tables : users, informations, scolarite, module (→ scolarite), enseignement (→ module), introduction_ecole (→ scolarite), projet, image_projet, projet_langage, langages, frameworks, logiciels, competences, categorie_competences, module_competence, carriere_visee, carriere_competence, experience_pro
- La fonctionnalité **visites** (table, modèle, controller, routes, `trackVisit()`) a été **supprimée le 16/11/2026** : elle était inutilisée, cassée et vulnérable. Ne pas la migrer.
- Config : `config/configJWT.php` lit `.env` à la racine (clé JWT). **Le `.env` manque en local**, à créer.

## Contrat d'API (phase 1, terminée)
- Inventaire complet dans `docs/api-contract.md`, snapshots de référence dans `snapshots/old/` (script `scripts/snapshots.sh`, JSON normalisés avec `jq -S`, fichier `.status` par route)
- Réponses : `{ success, message, data }`. Erreurs : `{ success: false, message }` **sans clé `data`**, codes 401/404/422/500. Création : 201. Suppression : `data: null`.
- Auth : JWT dans le **cookie HttpOnly `jwt_token`** (ou `Authorization: Bearer`). Le front dépend du cookie, ce qui pèse dans le choix JWT ou Sanctum (Sanctum SPA obligerait à modifier `apiFetch`).
- Routes : `POST /api/auth/login`, `POST /api/auth/logout`, `GET /api/auth/me`, puis un CRUD par ressource (GET publics, POST/PUT/DELETE protégés)

### Pièges identifiés pour Eloquent
1. Dates au format `"Y-m-d"` : pas de cast `date` (ou `date:Y-m-d`)
2. `projets.categorie_name` vient d'un `LEFT JOIN categorie_competences` : à reproduire avec `leftJoin` ou une Resource
3. Tables au singulier : `protected $table` dans chaque modèle
4. Pas de `created_at`/`updated_at` : `public $timestamps = false`
5. Encodage JSON : comparer avec `jq -S`
6. Ordre des listes : `id DESC` partout, **sauf** `EnseignementModel::byModule` et `IntroductionEcoleModel` (`ASC`)
7. id et clés étrangères en `number`, `modules_count` en `number`, champs vides en `null`

### Dette technique (à traiter après la migration, pas pendant)
- `user_id` exposé dans les réponses publiques
- `name` et `nom` incohérents selon les tables
- `PUT /api/competences` sans `nom`
- `ModuleCompetenceController` sans route

## Déploiement (état actuel)
- cPanel Git : clone dans `~/repositories/Portfolio2026`, puis « Mettre à jour depuis le distant » et « Déployer la validation HEAD »
- **`.cpanel.yml` en liste blanche** : copie uniquement `admin`, `api`, `assets`, `src`, `config`, `*.html` et `.htaccess` vers `~/mehiaoui-lab.fr/`. Il ne copie **jamais** `database`, `logs`, `.env` ni `vendor` (ces éléments vivent uniquement en prod).
- `.htaccess` racine : redirection HTTPS **en premier**, blocage des fichiers cachés (sauf `.well-known`), des dossiers internes (`src|config|database|logs|tests|scripts|snapshots|vendor`) et des fichiers racine non publics, puis routage `/api/*`
- `api/.htaccess` **remplace** les règles de la racine pour `/api/*` : la redirection HTTPS y est recopiée et `routes.php` y est bloqué
- `logs/.htaccess` (`Require all denied`) créé à la main en prod ; `logs/` est retiré de Git
- Sauvegarde de la base hors du site : `~/portfolio-SAUVEGARDE-16nov.sqlite` (131072 octets), plus `database/portfolio.sqlite.bak`

## Incidents du 16-17/11/2026 (post-mortem à rédiger par Mohamed : `docs/incidents/2026-11-16-base-ecrasee.md`)
1. **Base de prod écrasée (deux fois)** : une commande PDO lancée dans le clone y a créé un `portfolio.sqlite` vide, que l'ancien `.cpanel.yml` (« copie tout ») a copié en prod. Restauration depuis le `.bak` de 22:17. La deuxième fois, le déploiement a été fait avant que la liste blanche (PR #13) soit sur `main`.
2. **`.htaccess` écrasé** par un `printf > .htaccess` lancé dans le mauvais dossier (site entièrement en 403). Restauré avec `git show HEAD:.htaccess`.
3. **Logs publics** (`logs/` non bloqué, contenant des IP de visiteurs)
4. **HTTPS contourné sur l'API**, à cause de la règle placée après le routage, puis de `api/.htaccess` qui remplaçait les règles de la racine

### Règles retenues
- Sauvegarder avant toute modification ; vérifier `pwd` et utiliser des chemins absolus pour écrire sur le serveur (`>` écrase sans prévenir)
- Ne jamais modifier le clone `~/repositories` à la main ; tout passe par le PC, puis `git push`
- Une correction n'est en prod que si elle est sur `main` **et** déployée ; avant de déployer, vérifier `git show HEAD:.cpanel.yml`
- `git status` ne montre pas les fichiers ignorés (`.sqlite`) : utiliser `ls`
- Lire les logs avant de toucher à quoi que ce soit ; « Terminé » ne veut pas dire « réparé »
- Listes blanches plutôt que listes noires
- Une faille en prod se corrige immédiatement (branche `hotfix/*` fusionnée dans `main` et `develop`) ; le reste suit la règle « reproduire à l'identique, améliorer après »

## Feuille de route
| Phase | Statut |
|---|---|
| 0. Stratégie (progressive / Strangler Fig, Laravel dans `backend/`, branche `feature/laravel-migration`) | ✅ |
| 1. Figer le contrat (inventaire, snapshots, pièges) | ✅ (reste : régénérer les snapshots sans `visites` avec `/api/auth/me` comme test 401, créer le `.env` local, commiter) |
| 2. Installer Laravel dans `backend/`, branché sur la base SQLite | ⏭️ prochaine étape |
| 3. Modèles Eloquent + migrations | |
| 4. Routes GET + comparaison avec les snapshots | |
| 5. Auth (JWT dans le cookie conseillé, pour ne pas toucher au front) | |
| 6. Routes d'écriture, validation (422 au format du contrat), uploads | |
| 7. Déploiement o2switch (seul `public/` exposé, base hors du dossier déployé), puis GitHub Action | |
| 8. Bascule avec plan de retour arrière, puis suppression de l'ancien code | |