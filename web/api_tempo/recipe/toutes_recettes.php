<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=UTF-8');

require_once(__DIR__ . "/../../modele/DAO/RecipeDAO.class.php");

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

$dao = new RecipeDAO();
$recipes = $dao->getRecipesPaginated($limit, $offset); 

echo json_encode($recipes);
?>

