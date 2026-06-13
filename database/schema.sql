-- Base de données sqlite pour le projet : portfolio fullstack --
-- Utiliser VARCHAR(255) pour les champs de texte courts, TEXT pour les champs plus longs --

-- Tables sans clés étrangères --
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categorie_competences (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS langages (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    icon VARCHAR(255),
    pourcentage INTEGER NULL CHECK(pourcentage >= 0 AND pourcentage <= 100),
    categorie_id INTEGER NOT NULL,
    FOREIGN KEY (categorie_id) REFERENCES categorie_competences(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS visites (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ip_address_hash VARCHAR(64) NOT NULL,
    visited_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    page VARCHAR(100) NOT NULL,
    user_agent VARCHAR(255),
    referrer VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS frameworks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    icon VARCHAR(255),
    pourcentage INTEGER NULL CHECK(pourcentage >= 0 AND pourcentage <= 100),
    categorie_id INTEGER NOT NULL,
    FOREIGN KEY (categorie_id) REFERENCES categorie_competences(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS logiciels (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    icon VARCHAR(255),
    pourcentage INTEGER NULL CHECK(pourcentage >= 0 AND pourcentage <= 100),
    categorie_id INTEGER NOT NULL,
    FOREIGN KEY (categorie_id) REFERENCES categorie_competences(id) ON DELETE CASCADE
);

-- Tables avec clés étrangères des premieres tables --
-- Informations et Competences --

CREATE TABLE IF NOT EXISTS informations (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    description TEXT NOT NULL,
    avatar VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS competences (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    categorie_id INTEGER NOT NULL,
    nom VARCHAR(255) NOT NULL,
    pourcentage INTEGER NULL CHECK(pourcentage >= 0 AND pourcentage <= 100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categorie_competences(id) ON DELETE CASCADE
);

-- Puis le niveau suivant : Scolarite, Projet, Carriere, Experience_Pro (→ Informations) --

-- Scolarité : intitule,niveau,etablissement,ville,description, date_debut, date_fin, user_id --
CREATE TABLE IF NOT EXISTS scolarite (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    intitule VARCHAR(255) NOT NULL,
    niveau VARCHAR(255) NOT NULL,
    etablissement VARCHAR(255) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    description TEXT,
    date_debut DATE NOT NULL,
    date_fin DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Projet : titre, description, image_description, difficulté, lien, categorie_id, date_debut, date_fin, user_id --
CREATE TABLE IF NOT EXISTS projet (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    but TEXT,
    image_description VARCHAR(255),
    difficulte VARCHAR(50),
    lien VARCHAR(255),
    categorie_id INTEGER,
    date_debut DATE NOT NULL,
    date_fin DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categorie_competences(id) ON DELETE SET NULL
);

-- Carrière_visée : intitule, description, interet, user_id --
CREATE TABLE IF NOT EXISTS carriere_visee (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    intitule VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    interet TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Experience_Pro : intitule, entreprise, ville, description, date_debut, date_fin, type_contrat, user_id --
CREATE TABLE IF NOT EXISTS experience_pro (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    intitule VARCHAR(255) NOT NULL,
    entreprise VARCHAR(255) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    description TEXT,
    date_debut DATE NOT NULL,
    date_fin DATE,
    type_contrat VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Module (→ Scolarite), Image_Projet (→ Projet) --
--Module : intitule, niveau, description --
CREATE TABLE IF NOT EXISTS module (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    scolarite_id INTEGER NOT NULL,
    intitule VARCHAR(255) NOT NULL,
    niveau VARCHAR(255) NOT NULL,
    description TEXT,
    FOREIGN KEY (scolarite_id) REFERENCES scolarite(id) ON DELETE CASCADE
);

-- Image_projet: image_url, legende, ordre --
CREATE TABLE IF NOT EXISTS image_projet (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    projet_id INTEGER NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    legende VARCHAR(255),
    ordre INTEGER NOT NULL,
    FOREIGN KEY (projet_id) REFERENCES projet(id) ON DELETE CASCADE
);

-- les tables de liaison : Module_Competence, Projet_Langage, Carriere_Competence --

-- Enseignement : nom, description, module_id --
CREATE TABLE IF NOT EXISTS enseignement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    module_id INTEGER NOT NULL,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    FOREIGN KEY (module_id) REFERENCES module(id) ON DELETE CASCADE
);

-- Projet_Langage : projet_id, langage_id --
CREATE TABLE IF NOT EXISTS projet_langage (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    projet_id INTEGER NOT NULL,
    langage_id INTEGER NOT NULL,
    UNIQUE(projet_id, langage_id),
    FOREIGN KEY (projet_id) REFERENCES projet(id) ON DELETE CASCADE,
    FOREIGN KEY (langage_id) REFERENCES langages(id) ON DELETE CASCADE
);

-- Introduction Ecole : image, description, nb_annees, titre_court (→ scolarite) --
CREATE TABLE IF NOT EXISTS introduction_ecole (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    scolarite_id INTEGER NOT NULL,
    titre_court VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    description_intro TEXT,
    nb_annees INTEGER NOT NULL,
    FOREIGN KEY (scolarite_id) REFERENCES scolarite(id) ON DELETE CASCADE
);

-- Carriere_Competence : carriere_id, competence_id --
CREATE TABLE IF NOT EXISTS carriere_competence (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    carriere_id INTEGER NOT NULL,
    competence_id INTEGER NOT NULL,
    UNIQUE(carriere_id, competence_id),
    FOREIGN KEY (carriere_id) REFERENCES carriere_visee(id) ON DELETE CASCADE,
    FOREIGN KEY (competence_id) REFERENCES competences(id) ON DELETE CASCADE
);
