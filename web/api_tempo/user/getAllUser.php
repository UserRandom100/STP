<?php 
// Démarrer la session pour accéder à la variable $_SESSION
session_start();

//----------------------------------------------------------------------
//      cette requette retourne la liste de toute les recettes de la BD
//      lien: http://localhost/tpfinal/SwipeTonSouper/web/api_tempo/recipe/toutes_recettes.php
// SwipeTonSouper__MySQL
//----------------------------------------------------------------------
// INCLUSIONS
include_once(__DIR__ . "/../../modele/DAO/UserDAO.class.php");
include_once(__DIR__ . "/../../modele/user.class.php");
include_once(__DIR__ . "/../../modele/DAO/IngredientDAO.class.php");
include_once(__DIR__ . "/../../modele/recipeIngredient.class.php");
include_once(__DIR__ . "/../../modele/recipeAllergy.class.php");

try {
    // Vérification de la requête JSON
    $tabuser= array();
    $user = UserDAO::findAll();

    
    
    // Réponse de succès
    http_response_code(201); // Created
    echo json_encode(array("message" => "User aquerie avec succes!", "user" => $user));
} catch (Exception $e) {
    // Gestion des erreurs
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Une erreur est survenue : " . $e->getMessage()));
}
?>