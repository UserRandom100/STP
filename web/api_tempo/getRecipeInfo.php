<?php
// Charger la clé API depuis le fichier de configuration
require_once 'configApiKey.php'; 

// Récupérer l'ID de la recette depuis le paramètre de l'URL
$recipeId = isset($_GET['recipeId']) ? $_GET['recipeId'] : null;

if ($recipeId === null) {
    die('ID de recette non spécifié.');
}

// URL pour récupérer les informations détaillées de la recette
$infoUrl = "https://api.spoonacular.com/recipes/{$recipeId}/information?apiKey={$apiKey}&language=fr";

// Faire l'appel à l'API
$response = file_get_contents($infoUrl);

// Vérifier si la requête a réussi
if ($response === FALSE) {
    die('Erreur de récupération des détails de la recette.');
}

// Retourner la réponse JSON au frontend
echo $response;
?>
