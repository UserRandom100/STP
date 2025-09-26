<?php
// Pour voir dans un navigateur l'image binaire d'une recette (ex: id_recipe=32),
// vous pouvez utiliser l'URL suivante :
// http://localhost/SwipeTonSouper/web/api_tempo/image/afficher_image_binaire.php?id_recipe=32
// SwipeTonSouper_MySQL

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

include_once(__DIR__ . "/../../modele/DAO/RecipeDAO.class.php");
include_once(__DIR__ . "/../../modele/recipe.class.php");

// Récupérer l'ID de la recette ou de l'image que vous souhaitez afficher
$recipeId = isset($_GET['id_recipe']) ? (int) $_GET['id_recipe'] : 0;

if ($recipeId > 0) {

    $recipe = RecipeDAO::findById($recipeId);
    if ($recipe === null) {
        echo json_encode(["error" => "Recette non trouvée."]);
        exit;
    }

    $imageData = $recipe->getImageAndroid(); // Récupérer l'image binaire de la recette
    if ($imageData) {
        // Convertir l'image binaire en format base64
        $imageDataBase64 = base64_encode($imageData);
        $imageType = 'image/jpeg';  // Ou 'image/png' selon votre type d'image

        // Afficher l'image en base64 dans une balise img
        echo '<img src="data:' . $imageType . ';base64,' . $imageDataBase64 . '" alt="Recipe Image" />';
    } else {
        echo json_encode(["error" => "Aucune imageBinaire (imageAndroid) trouvée pour cette recette."]);
    }
    
} else {
    echo "ID de recette invalide.";
}

?>
