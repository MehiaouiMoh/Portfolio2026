-- ============================================================
-- SEED EXEMPLE — données anonymisées pour démonstration
-- Copier ce fichier en seed.sql et remplacer les valeurs
-- avant d'exécuter : php database/scripts/reset_db.php
-- ============================================================

-- 1. users
-- Générer le hash via : password_hash('votre_mot_de_passe', PASSWORD_BCRYPT)
INSERT INTO users (username, email, password) VALUES
    ('admin', 'votre@email.com', '$2y$10$REMPLACER_PAR_UN_VRAI_HASH_BCRYPT_GENERE_EN_PHP');

-- 2. categorie_competences
INSERT INTO categorie_competences (name) VALUES
    ('Frontend'),
    ('Backend'),
    ('Base de données'),
    ('Ux/Ui'),
    ('DevOps'),
    ('Déploiement'),
    ('Développement mobile'),
    ('Automatisation IA'),
    ('Fullstack');

-- 3. langages
-- Déposer les icônes dans assets/competencesIcones/
INSERT INTO langages (name, icon, pourcentage, categorie_id) VALUES
    ('HTML',       'assets/competencesIcones/html.png',    NULL, 1),
    ('CSS',        'assets/competencesIcones/css.png',     NULL, 1),
    ('JavaScript', 'assets/competencesIcones/js.png',      NULL, 1),
    ('PHP',        'assets/competencesIcones/php.png',     NULL, 2),
    ('SQL',        'assets/competencesIcones/sql.png',     NULL, 3),
    ('Flutter',    'assets/competencesIcones/flutter.jpg', NULL, 7);

-- 4. frameworks
INSERT INTO frameworks (name, icon, pourcentage, categorie_id) VALUES
    ('Laravel', NULL, NULL, 9);

-- 5. logiciels
INSERT INTO logiciels (name, icon, pourcentage, categorie_id) VALUES
    ('PhpMyAdmin',   'assets/competencesIcones/phpMyAdmin.jpg', NULL, 3),
    ('PostgreSQL',   'assets/competencesIcones/postgresql.png', NULL, 3),
    ('Figma',        'assets/competencesIcones/figma.jpg',      NULL, 4),
    ('CI/CD GitHub', 'assets/competencesIcones/github.png',     NULL, 5),
    ('n8n',          'assets/competencesIcones/n8n.jpg',        NULL, 8),
    ('Docker',       'assets/competencesIcones/docker.png',     NULL, 6),
    ('Make',         NULL,                                      NULL, 8);

-- 6. informations personnelles
INSERT INTO informations (user_id, nom, prenom, telephone, description, avatar) VALUES
    (1, 'Votre Nom', 'Votre Prénom', NULL,
     'Votre description personnelle ici.',
     NULL);

-- 7. competences
INSERT INTO competences (user_id, categorie_id, nom, pourcentage) VALUES
    (1, 2, 'API REST',              NULL),
    (1, 5, 'Test unitaire PHPUnit', NULL),
    (1, 6, 'Docker',                NULL);

-- 8. scolarite
INSERT INTO scolarite (user_id, intitule, niveau, etablissement, ville, description, date_debut, date_fin) VALUES
    (1, 'Intitulé de la formation', 'Niveau (ex: Licence 1)',
     'Nom de l''établissement', 'Ville',
     'Description de la formation.',
     'AAAA-MM-JJ', 'AAAA-MM-JJ'),

    (1, 'BUT Informatique — Réalisation d''applications', 'BUT 3',
     'Nom de l''IUT', 'Ville',
     'Description de la formation en cours.',
     '2025-09-01', '2026-06-22');

-- 9. introduction_ecole (FK → scolarite)
-- Images à déposer dans assets/images/scolarite/
INSERT INTO introduction_ecole (scolarite_id, titre_court, image, description_intro, nb_annees) VALUES
    (2, 'BUT Informatique',
     'assets/images/scolarite/informatique.jpg',
     'Votre texte de présentation de l''école.',
     3),
    (1, 'Licence Electronique',
     'assets/images/scolarite/electronic.jpg',
     'Votre texte de présentation de la formation.',
     3);

-- 10. projet
INSERT INTO projet (user_id, titre, description, image_description, difficulte, lien, date_debut, date_fin) VALUES
    (1, 'Nom du projet',
     'Description du projet.',
     NULL, 'Difficile', NULL, 'AAAA-MM-JJ', 'AAAA-MM-JJ');

-- 11. module (FK → scolarite) — exemple pour BUT 3 (scolarite_id = 2)
INSERT INTO module (scolarite_id, intitule, niveau, description) VALUES
    (2, 'Réaliser un développement d''application',   'Semestre 5', 'Description du module.'),
    (2, 'Optimiser des applications informatiques',   'Semestre 5', 'Description du module.'),
    (2, 'Travailler dans une équipe informatique',    'Semestre 5', 'Description du module.'),
    (2, 'Réaliser un développement d''applications',  'Semestre 6', 'Description du module.'),
    (2, 'Collaborer',                                 'Semestre 6', 'Description du module.');

-- 12. enseignements (FK → module)
INSERT INTO enseignement (module_id, nom, description) VALUES
    (1, 'Nom de l''enseignement', 'Description de l''enseignement.'),
    (2, 'Nom de l''enseignement', 'Description de l''enseignement.');

-- 13. experience_pro
INSERT INTO experience_pro (user_id, intitule, entreprise, ville, description, date_debut, date_fin, type_contrat) VALUES
    (1, 'Intitulé du poste', 'Nom entreprise', 'Ville',
     'Description du poste.',
     'AAAA-MM-JJ', 'AAAA-MM-JJ', 'Stage');

-- 14. projet_langage
INSERT INTO projet_langage (projet_id, langage_id) VALUES
    (1, 1), (1, 2);
