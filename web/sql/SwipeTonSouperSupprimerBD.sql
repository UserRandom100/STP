-- Suppression des données et des tables dans la base de données swipeMonSouper
DELETE FROM recette_allergies;
DELETE FROM user_created_recipes;
DELETE FROM user_saved_recipes;
DELETE FROM recette_ingredients;
DELETE FROM allergies;
DELETE FROM ingredients;
DELETE FROM recettes;
DELETE FROM users;
DELETE FROM roles;

-- Suppression des tables
DROP TABLE IF EXISTS recette_allergies;
DROP TABLE IF EXISTS user_created_recipes;
DROP TABLE IF EXISTS user_saved_recipes;
DROP TABLE IF EXISTS recette_ingredients;
DROP TABLE IF EXISTS allergies;
DROP TABLE IF EXISTS ingredients;
DROP TABLE IF EXISTS recettes;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

-- Suppression de la base de données
DROP DATABASE IF EXISTS swipeMonSouper;