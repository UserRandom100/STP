-- Créer la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS SwipeTonSouper;
USE SwipeTonSouper;

-- Supprimer les tables existantes
DROP TABLE IF EXISTS RecipeAllergy;
DROP TABLE IF EXISTS UserSavedRecipe;
DROP TABLE IF EXISTS RecipeIngredient;
DROP TABLE IF EXISTS Step;
DROP TABLE IF EXISTS Recipe;
DROP TABLE IF EXISTS Allergy;
DROP TABLE IF EXISTS Ingredient;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Role;

-- Table des rôles
CREATE TABLE Role (
    roleCode INT PRIMARY KEY,
    roleName VARCHAR(50) NOT NULL
);

-- Table des utilisateurs
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role INT,
    FOREIGN KEY (role) REFERENCES Role(roleCode) ON DELETE SET NULL
);

-- Table des allergies (1:1 avec User)
CREATE TABLE Allergy (
    id INT PRIMARY KEY,
    nuts TINYINT(1) DEFAULT 0,
    gluten TINYINT(1) DEFAULT 0,
    dairy TINYINT(1) DEFAULT 0,
    seafood TINYINT(1) DEFAULT 0,
    FOREIGN KEY (id) REFERENCES User(id) ON DELETE CASCADE
);

-- Table des recettes
CREATE TABLE Recipe (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    difficulty INT,
    prepTime INT,
    imagePath TEXT,
    id_creator INT,
    imageAndroid LONGBLOB,
    FOREIGN KEY (id_creator) REFERENCES User(id) ON DELETE SET NULL
);

-- Table des étapes
CREATE TABLE Step (
    recipeId INT,
    stepNumber INT,
    stepDescription TEXT,
    PRIMARY KEY (recipeId, stepNumber),
    FOREIGN KEY (recipeId) REFERENCES Recipe(id) ON DELETE CASCADE
);

-- Table des ingrédients
CREATE TABLE Ingredient (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    unitOfMeasure VARCHAR(50)
);

-- Table de liaison recette-ingrédients
CREATE TABLE RecipeIngredient (
    recipeId INT,
    ingredientId INT,
    quantity INT,
    unitOfMeasure VARCHAR(50),
    PRIMARY KEY (recipeId, ingredientId),
    FOREIGN KEY (recipeId) REFERENCES Recipe(id) ON DELETE CASCADE,
    FOREIGN KEY (ingredientId) REFERENCES Ingredient(id) ON DELETE CASCADE
);

-- Table de liaison utilisateur-recettes sauvegardées
CREATE TABLE UserSavedRecipe (
    userId INT,
    recipeId INT,
    PRIMARY KEY (userId, recipeId),
    FOREIGN KEY (userId) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (recipeId) REFERENCES Recipe(id) ON DELETE CASCADE
);

-- Table de liaison recette-allergie
CREATE TABLE RecipeAllergy (
    recipeId INT,
    allergyId INT,
    FOREIGN KEY (recipeId) REFERENCES Recipe(id) ON DELETE CASCADE,
    FOREIGN KEY (allergyId) REFERENCES Allergy(id) ON DELETE CASCADE
);

-- Insertion des rôles
INSERT INTO Role (roleCode, roleName) VALUES
(1, 'Administrateur'),
(2, 'Utilisateur Premium'),
(3, 'Utilisateur');

-- Insertion des utilisateurs
INSERT INTO User (name, email, password, role) VALUES
('Admin1', 'test@example.com', 'test', 1),
('Alice Dupont', 'alice.dupont@example.com', 'hashedpassword1', 2),
('Bob Martin', 'bob.martin@example.com', 'hashedpassword3', 3),
('Charlie Lemoine', 'charlie.lemoine@example.com', 'hashedpassword3', 3);

-- Insertion des allergies
INSERT INTO Allergy (id, nuts, gluten, dairy, seafood) VALUES
(1, 1, 0, 0, 1),
(2, 0, 1, 0, 0),
(3, 0, 0, 1, 1);

-- Insertion des recettes
INSERT INTO Recipe (title, description, difficulty, prepTime, imagePath, id_creator, imageAndroid) VALUES
('Spaghetti Carbonara', 'Un plat italien classique', 2, 20, 'images/spaghetti.jpg', 1, NULL),
('Salade César', 'Salade avec du poulet, des croûtons et une vinaigrette César', 1, 15, 'images/salade_cesar.jpg', 2, NULL),
('Tarte aux pommes', 'Un dessert aux pommes classique', 3, 60, 'images/tarte_pommes.jpg', 3, NULL);

-- Insertion des ingrédients
INSERT INTO Ingredient (name, unitOfMeasure) VALUES
('Pâtes', 'grammes'),
('Poulet', 'grammes'),
('Pommes', 'unités'),
('Crème', 'millilitres'),
('Fromage', 'grammes');

-- Insertion des liens recette-ingrédient
INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity, unitOfMeasure) VALUES
(1, 1, 200, 'grammes'),
(1, 4, 100, 'millilitres'),
(2, 2, 150, 'grammes'),
(3, 3, 4, 'unités');

-- Insertion des recettes sauvegardées
INSERT INTO UserSavedRecipe (userId, recipeId) VALUES
(1, 1),
(2, 2),
(3, 3);

-- Insertion des associations recette-allergie
INSERT INTO RecipeAllergy (recipeId, allergyId) VALUES
(1, 1),
(2, 2),
(3, 3);

-- Insertion des étapes
INSERT INTO Step (recipeId, stepNumber, stepDescription) VALUES
(1, 1, 'Faire cuire les pâtes dans une grande casserole d’eau salée.'),
(1, 2, 'Faire frire la pancetta dans une poêle jusqu’à ce qu’elle soit croustillante.'),
(1, 3, 'Mélanger les pâtes avec la pancetta, ajouter la crème et le fromage râpé.'),
(1, 4, 'Servir immédiatement avec du parmesan râpé.'),
(2, 1, 'Faire cuire le poulet, puis le couper en morceaux.'),
(2, 2, 'Préparer la vinaigrette César en mélangeant mayonnaise, moutarde et autres ingrédients.'),
(2, 3, 'Mélanger les croûtons, la laitue et le poulet dans un bol.'),
(2, 4, 'Ajouter la vinaigrette César et mélanger la salade.');
