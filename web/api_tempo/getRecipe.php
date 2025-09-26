<?php
// Charger la clé API depuis le fichier de configuration
require_once 'configApiKey.php'; 

// Vérifiez si un paramètre "query" est passé
$query = isset($_GET['query']) ? $_GET['query'] : 'poulet riz';  // Valeur par défaut si rien n'est spécifié

// Encodage de la requête pour l'URL
$query = urlencode($query);

// URL de l'API pour rechercher les recettes (sans paramètres redondants)
$searchUrl = "https://api.spoonacular.com/recipes/complexSearch?query={$query}&apiKey={$apiKey}&language=fr";

// Faire l'appel à l'API
$response = file_get_contents($searchUrl);

// Vérifier si la requête a réussi
if ($response === FALSE) {
    die('Erreur de récupération des données de l\'API.');
}

// Retourner la réponse JSON au frontend
echo $response;
?>
