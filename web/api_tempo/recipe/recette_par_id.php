<?php 
// Démarrer la session pour accéder à la variable $_SESSION
session_start();

//----------------------------------------------------------------------
//      cette requette retourne la liste de toute les recettes de la BD
//      lien: http://localhost/tpfinal/SwipeTonSouper/web/api_tempo/recipe/recettes_par_id.php
// SwipeTonSouper__MySQL
//----------------------------------------------------------------------
// INCLUSIONS
include_once(__DIR__ . "/../../modele/DAO/RecipeDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/RecipeFullDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/IngredientDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/StepDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/RecipeIngredientDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/RecipeAllergyDAO.class.php");
include_once(__DIR__ . "/../../modele/recipe.class.php");
include_once(__DIR__ . "/../../modele/step.class.php");
include_once(__DIR__ . "/../../modele/recipeIngredient.class.php");
include_once(__DIR__ . "/../../modele/recipeAllergy.class.php");

try {
    // Vérification de la requête JSON
    if(empty($_GET) || !isset($_GET)){
        http_response_code(404); // Internal Server Error
        echo json_encode(array("message" => "Id recette non inscrit  "));
    }else
    {
    $id = $_GET["id_recette"];
    $fullRecettes = RecipeFullDAO::findFullRecipeById($id); // nous donne la recette avec ses ingrediant et ses etapes
    }
    
    // Réponse de succès
    http_response_code(201); // Created
    echo json_encode(array("message" => "Recette aquerie avec succes!", "recipes" => $fullRecettes));
} catch (Exception $e) {
    // Gestion des erreurs
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Une erreur est survenue : " . $e->getMessage()));
}
?>