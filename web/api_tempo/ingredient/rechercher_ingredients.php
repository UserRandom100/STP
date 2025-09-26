<?php
// HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// INCLUSIONS
include_once(__DIR__ . "/../../modele/DAO/IngredientDAO.class.php");
include_once(__DIR__ . "/../../modele/ingredient.class.php");

try {
    // Vérification que le paramètre 'query' existe et n'est pas vide
    if (!isset($_GET['query']) || empty($_GET['query'])) {
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Le paramètre 'query' est manquant ou vide."));
        exit();
    }

    // Récupération de la requête de recherche
    $query = $_GET['query'];

    // Recherche des ingrédients dans la base de données
    $ingredients = IngredientDAO::findByDescription($query);

    // Vérifier si des ingrédients ont été trouvés
    http_response_code(200); // OK
    if ($ingredients && count($ingredients) > 0) {
        echo json_encode($ingredients, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    } else {
        // Aucun ingrédient trouvé
        echo json_encode([]); // Retourne un tableau vide
    }
} catch (Exception $e) {
    // Gestion des erreurs
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Une erreur est survenue : " . $e->getMessage()));
}
?>
