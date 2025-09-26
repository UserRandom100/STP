-- Create the database
CREATE DATABASE IF NOT EXISTS SwipeTonSouper CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Select the database
USE SwipeTonSouper;

SET foreign_key_checks = 0;


-- Drop dependent tables before dropping the Recipe table
DROP TABLE IF EXISTS RecipeAllergy;
DROP TABLE IF EXISTS UserSavedRecipe;
DROP TABLE IF EXISTS UserCreatedRecipe;
DROP TABLE IF EXISTS RecipeIngredient;
DROP TABLE IF EXISTS Step;
DROP TABLE IF EXISTS Recipe; -- Main table to drop
DROP TABLE IF EXISTS Allergy;
DROP TABLE IF EXISTS Ingredient;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Role;

SET foreign_key_checks = 1;

-- Create the Roles table
CREATE TABLE Role (
    roleCode INT PRIMARY KEY CONSTRAINT pk_roleCode,          -- Primary key constraint
    roleName VARCHAR(50) NOT NULL      -- Role name (e.g., Admin, Premium User...)
);

-- Create the Users table
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY CONSTRAINT pk_userId,  -- Primary key constraint
    name VARCHAR(50) NOT NULL,           -- User's name
    email VARCHAR(255) NOT NULL UNIQUE,  -- Unique email address
    password VARCHAR(255) NOT NULL,      -- Hashed password
    role INT,
    FOREIGN KEY (role) REFERENCES Role(roleCode) 
        ON DELETE SET NULL CONSTRAINT fk_user_role
);

-- Create the Allergies table (1:1 relationship with User)
CREATE TABLE Allergy (
    id INT PRIMARY KEY CONSTRAINT pk_allergyId,              -- Primary key constraint
    nuts BOOLEAN DEFAULT FALSE,         -- Nut allergy
    gluten BOOLEAN DEFAULT FALSE,       -- Gluten allergy
    dairy BOOLEAN DEFAULT FALSE,        -- Dairy allergy
    seafood BOOLEAN DEFAULT FALSE,      -- Seafood allergy
    FOREIGN KEY (id) REFERENCES User(id) 
        ON DELETE CASCADE CONSTRAINT fk_allergy_user
);

-- Create the Recipes table
CREATE TABLE Recipe (
    id INT AUTO_INCREMENT PRIMARY KEY CONSTRAINT pk_recipeId,        -- Primary key constraint
    title VARCHAR(255) NOT NULL,        -- Recipe name
    description TEXT,                   -- Recipe description
    difficulty INT,                     -- Difficulty level (e.g., 1 to 5)
    prepTime INT                        -- Preparation time in minutes
);

-- Create the Steps table
CREATE TABLE Step (
    recipeId INT,                       -- Reference to the Recipe ID
    stepNumber INT,                     -- Step number in the recipe
    stepDescription TEXT,               -- Description of the step
    PRIMARY KEY (recipeId, stepNumber) CONSTRAINT pk_step,   -- Primary key constraint on combination
    FOREIGN KEY (recipeId) REFERENCES Recipe(id) 
        ON DELETE CASCADE CONSTRAINT fk_step_recipe
);

-- Create the Ingredients table
CREATE TABLE Ingredient (
    id INT AUTO_INCREMENT PRIMARY KEY CONSTRAINT pk_ingredientId,    -- Primary key constraint
    name VARCHAR(255) NOT NULL,          -- Ingredient name
    unitOfMeasure VARCHAR(50)           -- Unit of measurement (grams, ml, units...)
);

-- Create the Recipe-Ingredient junction table
CREATE TABLE RecipeIngredient (
    recipeId INT,
    ingredientId INT,
    quantity INT,                       -- Quantity of the ingredient in the recipe
    unitOfMeasure VARCHAR(50),           -- Unit of measurement (grams, ml, units...)
    PRIMARY KEY (recipeId, ingredientId) CONSTRAINT pk_recipe_ingredient, -- Primary key constraint on combination
    FOREIGN KEY (recipeId) REFERENCES Recipe(id) 
        ON DELETE CASCADE CONSTRAINT fk_recipeingredient_recipe,
    FOREIGN KEY (ingredientId) REFERENCES Ingredient(id) 
        ON DELETE CASCADE CONSTRAINT fk_recipeingredient_ingredient
);

-- Create the User-SavedRecipes junction table
CREATE TABLE UserSavedRecipe (
    userId INT,
    recipeId INT,
    PRIMARY KEY (userId, recipeId) CONSTRAINT pk_user_saved_recipe, -- Primary key constraint on combination
    FOREIGN KEY (userId) REFERENCES User(id) 
        ON DELETE CASCADE CONSTRAINT fk_usersavedrecipe_user,
    FOREIGN KEY (recipeId) REFERENCES Recipe(id) 
        ON DELETE CASCADE CONSTRAINT fk_usersavedrecipe_recipe
);

-- Create the User-CreatedRecipes junction table
CREATE TABLE UserCreatedRecipe (
    userId INT,
    recipeId INT,
    PRIMARY KEY (userId, recipeId) CONSTRAINT pk_user_created_recipe, -- Primary key constraint on combination
    FOREIGN KEY (userId) REFERENCES User(id) 
        ON DELETE CASCADE CONSTRAINT fk_usercreatedrecipe_user,
    FOREIGN KEY (recipeId) REFERENCES Recipe(id) 
        ON DELETE CASCADE CONSTRAINT fk_usercreatedrecipe_recipe
);

-- Create the Recipe-Allergy junction table
CREATE TABLE RecipeAllergy (
    recipeId INT,
    allergyId INT,
    FOREIGN KEY (recipeId) REFERENCES Recipe(id) 
        ON DELETE CASCADE CONSTRAINT fk_recipeallergy_recipe,
    FOREIGN KEY (allergyId) REFERENCES Allergy(id) 
        ON DELETE CASCADE CONSTRAINT fk_recipeallergy_allergy
);

-- Insert example data
INSERT INTO Role (roleCode, roleName) VALUES
(1, 'Admin'),
(2, 'Premium User'),
(3, 'User');

INSERT INTO User (name, email, password, role) VALUES
('Admin1', 'test@example.com', 'test', 2),
('Alice Dupont', 'alice.dupont@example.com', 'hashedpassword1', 2),
('Bob Martin', 'bob.martin@example.com', 'hashedpassword3', 3),
('Charlie Lemoine', 'charlie.lemoine@example.com', 'hashedpassword3', 3);

INSERT INTO Allergy (id, nuts, gluten, dairy, seafood) VALUES
(1, TRUE, FALSE, FALSE, TRUE),
(2, FALSE, TRUE, FALSE, FALSE),
(3, FALSE, FALSE, TRUE, TRUE);

INSERT INTO Recipe (title, description, difficulty, prepTime) VALUES
('Spaghetti Carbonara', 'A classic Italian dish', 2, 20),
('Caesar Salad', 'Salad with chicken, croutons, and Caesar dressing', 1, 15),
('Apple Pie', 'A classic apple dessert', 3, 60);

INSERT INTO Ingredient (name, unitOfMeasure) VALUES
('Pasta', 'grams'),
('Chicken', 'grams'),
('Apples', 'units'),
('Cream', 'milliliters'),
('Cheese', 'grams');

INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity, unitOfMeasure) VALUES
(1, 1, 200, 'grams'),
(1, 4, 100, 'milliliters'),
(2, 2, 150, 'grams'),
(3, 3, 4, 'units');

INSERT INTO UserSavedRecipe (userId, recipeId) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO UserCreatedRecipe (userId, recipeId) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO RecipeAllergy (recipeId, allergyId) VALUES
(1, 1),
(2, 2),
(3, 3);

-- Insert example steps for a recipe (ID = 1, for example, Spaghetti Carbonara)
INSERT INTO Step (recipeId, stepNumber, stepDescription) VALUES
(1, 1, 'Cook the pasta in a large pot of salted water.'),
(1, 2, 'Fry the pancetta in a pan until crispy.'),
(1, 3, 'Mix the pasta with the pancetta and add cream and grated cheese.'),
(1, 4, 'Serve immediately with grated parmesan.');

-- Insert steps for another recipe, for example, Caesar Salad (ID = 2)
INSERT INTO Step (recipeId, stepNumber, stepDescription) VALUES
(2, 1, 'Cook the chicken, then cut it into pieces.'),
(2, 2, 'Prepare the Caesar dressing by mixing mayonnaise, mustard, and other ingredients.'),
(2, 3, 'Mix the croutons, lettuce, and chicken in a bowl.'),
(2, 4, 'Add the Caesar dressing and toss the salad.');
