<?php 
// Démarrer la session pour accéder à la variable $_SESSION
session_start();

//----------------------------------------------------------------------
//      cette requette la recette dont le id corespont a 'id' dans le corp de la requette
//      lien: http://localhost/tpfinal/SwipeTonSouper/web/api_tempo/recipe/suprimer_recette_par_id.php
// SwipeTonSouper_MySQL
//----------------------------------------------------------------------
// INCLUSIONS
include_once(__DIR__ . "/../../modele/DAO/RecipeDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/IngredientDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/StepDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/RecipeIngredientDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/RecipeAllergyDAO.class.php");
include_once(__DIR__ . "/../../modele/recipe.class.php");
include_once(__DIR__ . "/../../modele/step.class.php");
include_once(__DIR__ . "/../../modele/recipeIngredient.class.php");
include_once(__DIR__ . "/../../modele/recipeAllergy.class.php");

try {
    // on prent les input de la requete envoyer (normalement un id)
    $inputData = json_decode(file_get_contents('php://input'), true);

    if($inputData == null || !isset($inputData))
    {
        http_response_code(501); // Internal Server Error
        echo json_encode(array("message" => "Une erreur est survenue : pas de recette à suprimer"));
    }
    else{
        $id = $inputData['id'];
        $recette = new Recipe($id, "", null, null, null, null);
    }
    
    //si on reussi a ajouter la recette
    if(RecipeDAO::delete($recette)){
        // Réponse de succès
        http_response_code(201); // Created
        echo json_encode(array("message" => "Recette suprimer avec succès."));
    }

    //reponse si pas succes
    else{
        http_response_code(502); // pas reussi a creer
        echo json_encode(array("message" => "Un probleme à été rencontré lors de la supression "));
    }
} catch (Exception $e) {
    // Gestion des erreurs
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Une erreur est survenue : " . $e->getMessage()));
}
?>