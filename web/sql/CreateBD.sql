-- -- Créer la base de données
-- IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'SwipeTonSouper')
-- BEGIN
--     CREATE DATABASE SwipeTonSouper;
-- END
-- GO

-- -- Sélectionner la base de données
-- USE swipeMonSouperDB;
-- GO

-- -- Désactiver les vérifications de clés étrangères
-- EXEC sp_msforeachtable "ALTER TABLE ? NOCHECK CONSTRAINT all";
-- GO

-- Supprimer les tables existantes si elles existent (ordre inverse des dépendances)
IF OBJECT_ID('dbo.RecipeAllergy', 'U') IS NOT NULL DROP TABLE dbo.RecipeAllergy;
IF OBJECT_ID('dbo.UserSavedRecipe', 'U') IS NOT NULL DROP TABLE dbo.UserSavedRecipe;
IF OBJECT_ID('dbo.RecipeIngredient', 'U') IS NOT NULL DROP TABLE dbo.RecipeIngredient;
IF OBJECT_ID('dbo.Step', 'U') IS NOT NULL DROP TABLE dbo.Step;
IF OBJECT_ID('dbo.Recipe', 'U') IS NOT NULL DROP TABLE dbo.Recipe;
IF OBJECT_ID('dbo.Allergy', 'U') IS NOT NULL DROP TABLE dbo.Allergy;
IF OBJECT_ID('dbo.Ingredient', 'U') IS NOT NULL DROP TABLE dbo.Ingredient;
IF OBJECT_ID('dbo.[User]', 'U') IS NOT NULL DROP TABLE dbo.[User];
IF OBJECT_ID('dbo.Role', 'U') IS NOT NULL DROP TABLE dbo.Role;
GO

-- Créer la table des rôles
CREATE TABLE dbo.Role (
    roleCode INT PRIMARY KEY,          -- Clé primaire pour le rôle
    roleName VARCHAR(50) NOT NULL      -- Nom du rôle (ex : Admin, Utilisateur Premium)
);
GO

-- Créer la table des utilisateurs
CREATE TABLE dbo.[User] (
    id INT IDENTITY(1,1) PRIMARY KEY,  -- Identifiant unique
    name VARCHAR(50) NOT NULL,         -- Nom de l'utilisateur
    email VARCHAR(255) NOT NULL UNIQUE,-- Email unique
    password VARCHAR(255) NOT NULL,    -- Mot de passe (haché)
    role INT,                          -- Clé étrangère vers Role
    FOREIGN KEY (role) REFERENCES dbo.Role(roleCode) ON DELETE SET NULL
);
GO

-- Créer la table des allergies (relation 1:1 avec User)
CREATE TABLE dbo.Allergy (
    id INT PRIMARY KEY,                -- Correspond à l'ID de l'utilisateur
    nuts BIT DEFAULT 0,                -- Allergie aux noix
    gluten BIT DEFAULT 0,              -- Allergie au gluten
    dairy BIT DEFAULT 0,               -- Allergie aux produits laitiers
    seafood BIT DEFAULT 0,             -- Allergie aux fruits de mer
    FOREIGN KEY (id) REFERENCES dbo.[User](id) ON DELETE CASCADE
);
GO

-- Créer la table des recettes
CREATE TABLE dbo.Recipe (
    id INT IDENTITY(1,1) PRIMARY KEY,  -- Identifiant unique
    title VARCHAR(255) NOT NULL,       -- Titre de la recette
    description TEXT,                  -- Description de la recette
    difficulty INT,                    -- Difficulté (ex : 1 à 5)
    prepTime INT,                      -- Temps de préparation en minutes
    imagePath TEXT,                    -- Chemin de l'image de la recette
    id_creator INT,                    -- ID du créateur (utilisateur)
    imageAndroid VARBINARY (MAX) NULL,
    FOREIGN KEY (id_creator) REFERENCES dbo.[User](id) ON DELETE SET NULL
);
GO

-- Créer la table des étapes
CREATE TABLE dbo.Step (
    recipeId INT,                      -- ID de la recette
    stepNumber INT,                    -- Numéro de l'étape
    stepDescription TEXT,              -- Description de l'étape
    PRIMARY KEY (recipeId, stepNumber),
    FOREIGN KEY (recipeId) REFERENCES dbo.Recipe(id) ON DELETE CASCADE
);
GO

-- Créer la table des ingrédients
CREATE TABLE dbo.Ingredient (
    id INT IDENTITY(1,1) PRIMARY KEY,  -- Identifiant unique
    name VARCHAR(255) NOT NULL,        -- Nom de l'ingrédient
    unitOfMeasure VARCHAR(50)          -- Unité de mesure
);
GO

-- Créer la table de liaison Recette-Ingrédient
CREATE TABLE dbo.RecipeIngredient (
    recipeId INT,
    ingredientId INT,
    quantity INT,                      -- Quantité
    unitOfMeasure VARCHAR(50),         -- Unité de mesure
    PRIMARY KEY (recipeId, ingredientId),
    FOREIGN KEY (recipeId) REFERENCES dbo.Recipe(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredientId) REFERENCES dbo.Ingredient(id) ON DELETE CASCADE
);
GO

-- Créer la table de liaison Utilisateur-Recettes enregistrées
CREATE TABLE dbo.UserSavedRecipe (
    userId INT,
    recipeId INT,
    PRIMARY KEY (userId, recipeId),
    FOREIGN KEY (userId) REFERENCES dbo.[User](id) ON DELETE CASCADE,
    FOREIGN KEY (recipeId) REFERENCES dbo.Recipe(id) ON DELETE CASCADE
);
GO

-- Créer la table de liaison Recette-Allergie
CREATE TABLE dbo.RecipeAllergy (
    recipeId INT,
    allergyId INT,
    FOREIGN KEY (recipeId) REFERENCES dbo.Recipe(id) ON DELETE CASCADE,
    FOREIGN KEY (allergyId) REFERENCES dbo.Allergy(id) ON DELETE CASCADE
);
GO

-- Insérer des rôles
INSERT INTO dbo.Role (roleCode, roleName) VALUES
(1, 'Administrateur'),
(2, 'Utilisateur Premium'),
(3, 'Utilisateur');
GO

-- Insérer des utilisateurs
INSERT INTO dbo.[User] (name, email, password, role) VALUES
('Admin1', 'test@example.com', 'test', 1),
('Alice Dupont', 'alice.dupont@example.com', 'hashedpassword1', 2),
('Bob Martin', 'bob.martin@example.com', 'hashedpassword3', 3),
('Charlie Lemoine', 'charlie.lemoine@example.com', 'hashedpassword3', 3);
GO

-- Insérer des allergies
INSERT INTO dbo.Allergy (id, nuts, gluten, dairy, seafood) VALUES
(1, 1, 0, 0, 1),
(2, 0, 1, 0, 0),
(3, 0, 0, 1, 1);
GO

-- Insérer des recettes 
INSERT INTO dbo.Recipe (title, description, difficulty, prepTime, imagePath, id_creator, imageAndroid) VALUES
('Spaghetti Carbonara', 'Un plat italien classique', 2, 20, 'images/spaghetti.jpg', 1, NULL),
('Salade César', 'Salade avec du poulet, des croûtons et une vinaigrette César', 1, 15, 'images/salade_cesar.jpg', 2, NULL),
('Tarte aux pommes', 'Un dessert aux pommes classique', 3, 60, 'images/tarte_pommes.jpg', 3, NULL);
GO

-- Insérer des ingrédients
INSERT INTO dbo.Ingredient (name, unitOfMeasure) VALUES
('Pâtes', 'grammes'),
('Poulet', 'grammes'),
('Pommes', 'unités'),
('Crème', 'millilitres'),
('Fromage', 'grammes');
GO

-- Insérer des liens recette-ingrédients
INSERT INTO dbo.RecipeIngredient (recipeId, ingredientId, quantity, unitOfMeasure) VALUES
(1, 1, 200, 'grammes'),
(1, 4, 100, 'millilitres'),
(2, 2, 150, 'grammes'),
(3, 3, 4, 'unités');
GO

-- Insérer des recettes enregistrées par des utilisateurs
INSERT INTO dbo.UserSavedRecipe (userId, recipeId) VALUES
(1, 1),
(2, 2),
(3, 3);
GO

-- Insérer des associations recette-allergie
INSERT INTO dbo.RecipeAllergy (recipeId, allergyId) VALUES
(1, 1),
(2, 2),
(3, 3);
GO

-- Insérer des étapes pour les recettes
INSERT INTO dbo.Step (recipeId, stepNumber, stepDescription) VALUES
(1, 1, 'Faire cuire les pâtes dans une grande casserole d’eau salée.'),
(1, 2, 'Faire frire la pancetta dans une poêle jusqu’à ce qu’elle soit croustillante.'),
(1, 3, 'Mélanger les pâtes avec la pancetta, ajouter la crème et le fromage râpé.'),
(1, 4, 'Servir immédiatement avec du parmesan râpé.'),
(2, 1, 'Faire cuire le poulet, puis le couper en morceaux.'),
(2, 2, 'Préparer la vinaigrette César en mélangeant mayonnaise, moutarde et autres ingrédients.'),
(2, 3, 'Mélanger les croûtons, la laitue et le poulet dans un bol.'),
(2, 4, 'Ajouter la vinaigrette César et mélanger la salade.');
GO