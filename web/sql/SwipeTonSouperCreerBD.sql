-- Création de la base de données
CREATE DATABASE IF NOT EXISTS SwipeTonSouper CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sélection de la base de données
USE SwipeTonSouper;

SET foreign_key_checks = 0;

-- Suppression des tables dépendantes avant de supprimer la table Recette
DROP TABLE IF EXISTS RecetteAllergie;
DROP TABLE IF EXISTS UserSavedRecipe;
DROP TABLE IF EXISTS UserCreatedRecipe;
DROP TABLE IF EXISTS RecetteIngredient;
DROP TABLE IF EXISTS Etape;
DROP TABLE IF EXISTS Recette; -- Table principale à supprimer
DROP TABLE IF EXISTS Allergie;
DROP TABLE IF EXISTS Ingredient;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Role;

SET foreign_key_checks = 1;

-- Création de la table des rôles
CREATE TABLE Role (
    codeRole INT PRIMARY KEY,          -- Code unique du rôle
    nomRole VARCHAR(50) NOT NULL       -- Nom du rôle (ex: Admin, Utilisateur...)
);

-- Création de la table des utilisateurs
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Identifiant unique de l'utilisateur
    nom VARCHAR(50) NOT NULL,           -- Nom de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE, -- Adresse e-mail unique
    password VARCHAR(255) NOT NULL,     -- Mot de passe haché
    role INT,
    FOREIGN KEY (role) REFERENCES Role(codeRole) ON DELETE SET NULL
);

-- Création de la table des allergies (relation 1:1 avec user)
CREATE TABLE Allergie (
    id INT PRIMARY KEY,                 -- Même ID que User pour liaison 1:1
    noix BOOLEAN DEFAULT FALSE,         -- Allergie aux noix
    gluten BOOLEAN DEFAULT FALSE,       -- Allergie au gluten
    produitsLaitiers BOOLEAN DEFAULT FALSE, -- Allergie aux produits laitiers
    fruitsDeMer BOOLEAN DEFAULT FALSE,    -- Allergie aux fruits de mer
    FOREIGN KEY (id) REFERENCES User(id) ON DELETE CASCADE -- Suppression auto si User supprimé
);

-- Création de la table des recettes
CREATE TABLE Recette (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,       -- Nom de la recette
    description TEXT,                  -- Description de la recette
    difficulte INT,                    -- Niveau de difficulté (ex: 1 à 5)
    tempsdepreparation INT,            -- Temps en minutes
    id_createur INT,                   -- id du createur de la recette
    FOREIGN KEY (id_createur) REFERENCES User(id) ON DELETE SET NULL
);

-- Création de la table des étapes
CREATE TABLE Etape (
    idRecette INT,                     -- Référence à l'id de la recette
    numeroEtape INT,                   -- Numéro de l'étape dans la recette
    descriptionEtape TEXT,             -- Description de l'étape
    PRIMARY KEY (idRecette, numeroEtape),  -- La clé primaire est composée de l'id de la recette et du numéro de l'étape
    FOREIGN KEY (idRecette) REFERENCES Recette(id) ON DELETE CASCADE  -- Suppression automatique des étapes si la recette est supprimée
);

-- Création de la table des ingrédients
CREATE TABLE Ingredient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,          -- Nom de l'ingrédient
    uniteDeMesure VARCHAR(50)           -- Unité de mesure (grammes, ml, unités...)
);

-- Création de la table de liaison entre recettes et ingrédients
CREATE TABLE RecetteIngredient (
    recetteId INT,
    ingredientId INT,
    quantite INT,                       -- Quantité de l'ingrédient dans la recette
    uniteDeMesure VARCHAR(50),           -- Unité de mesure (grammes, ml, unités...)
    PRIMARY KEY (recetteId, ingredientId),
    FOREIGN KEY (recetteId) REFERENCES Recette(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredientId) REFERENCES Ingredient(id) ON DELETE CASCADE
);

-- Création de la table de liaison pour les recettes sauvegardées par les utilisateurs
CREATE TABLE UserSavedRecipe (
    userId INT,
    recetteId INT,
    PRIMARY KEY (userId, recetteId),
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (recetteId) REFERENCES Recette(id) ON DELETE CASCADE
);

-- Création de la table de liaison pour les recettes créées par les utilisateurs
CREATE TABLE UserCreatedRecipe (
    userId INT,
    recetteId INT,
    PRIMARY KEY (userId, recetteId),
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (recetteId) REFERENCES Recette(id) ON DELETE CASCADE
);

-- Création de la table de liaison entre recettes et allergies
CREATE TABLE RecetteAllergie (
    recetteId INT,
    allergyId INT,
    FOREIGN KEY (recetteId) REFERENCES Recette(id) ON DELETE CASCADE,
    FOREIGN KEY (allergyId) REFERENCES Allergie(id) ON DELETE CASCADE
);

-- Insertion d'exemples de données
INSERT INTO Role (codeRole, nomRole) VALUES
(1, 'Admin'),
(2, 'Utilisateur premium'),
(3, 'Utilisateur');

INSERT INTO User (nom, email, password, role) VALUES
('Admin1', 'test@example.com', 'test', 2),
('Dupont', 'alice.dupont@example.com', 'hashedpassword1', 2),
('Martin', 'bob.martin@example.com', 'hashedpassword3', 3),
('Lemoine', 'charlie.lemoine@example.com', 'hashedpassword3', 3);

INSERT INTO Allergie (id, noix, gluten, produitsLaitiers, fruitsDeMer) VALUES
(1, TRUE, FALSE, FALSE, TRUE),
(2, FALSE, TRUE, FALSE, FALSE),
(3, FALSE, FALSE, TRUE, TRUE);

INSERT INTO Recette (titre, description, difficulte, tempsDePreparation) VALUES
('Spaghetti Carbonara', 'Un plat italien classique', 2, 20),
('Salade César', 'Salade avec poulet, croûtons et sauce César', 1, 15),
('Tarte aux pommes', 'Dessert classique aux pommes', 3, 60);

INSERT INTO Ingredient (nom, uniteDeMesure) VALUES
('Pâtes', 'grammes'),
('Poulet', 'grammes'),
('Pommes', 'unités'),
('Crème', 'millilitres'),
('Fromage', 'grammes');

INSERT INTO RecetteIngredient (recetteId, ingredientId, quantite, uniteDeMesure) VALUES
(1, 1, 200, 'grammes'),
(1, 4, 100, 'millilitres'),
(2, 2, 150, 'grammes'),
(3, 3, 4, 'unités');

INSERT INTO UserSavedRecipe (userId, recetteId) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO UserCreatedRecipe (userId, recetteId) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO RecetteAllergie (recetteId, allergyId) VALUES
(1, 1),
(2, 2),
(3, 3);

-- Insertion d'exemples d'étapes pour une recette (ID = 1, par exemple, Spaghetti Carbonara)
INSERT INTO Etape (idRecette, numeroEtape, descriptionEtape) VALUES
(1, 1, 'Faire cuire les pâtes dans une grande quantité d\'eau salée.'),
(1, 2, 'Faire revenir la pancetta dans une poêle jusqu\'à ce qu\'elle soit croustillante.'),
(1, 3, 'Mélanger les pâtes avec la pancetta et ajouter la crème et le fromage râpé.'),
(1, 4, 'Servir immédiatement avec du parmesan râpé.');

-- Insertion pour une autre recette, par exemple, Salade César (ID = 2)
INSERT INTO Etape (idRecette, numeroEtape, descriptionEtape) VALUES
(2, 1, 'Faire cuire le poulet, le couper en morceaux.'),
(2, 2, 'Préparer la sauce César en mélangeant mayonnaise, moutarde, et autres ingrédients.'),
(2, 3, 'Mélanger les croûtons, la laitue et le poulet dans un saladier.'),
(2, 4, 'Ajouter la sauce César et mélanger.');
