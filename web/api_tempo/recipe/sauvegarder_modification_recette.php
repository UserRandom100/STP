<?php
require_once("../../modele/DAO/RecipeDAO.class.php");
require_once("../../modele/DAO/RecipeIngredientDAO.class.php");
require_once("../../modele/DAO/StepDAO.class.php");
require_once("../../modele/recipe.class.php");
require_once("../../modele/recipeIngredient.class.php");
require_once("../../modele/step.class.php");

header('Content-Type: application/json; charset=UTF-8');

// Vérifie la méthode
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["error" => "Méthode non autorisée"]);
    exit;
}

$recipeId = filter_input(INPUT_POST, 'idRecette', FILTER_VALIDATE_INT);

if (!$recipeId) {
    http_response_code(400);
    echo json_encode(["error" => "ID de recette invalide"]);
    exit;
}

$recette = RecipeDAO::findById($recipeId);
if (!$recette) {
    http_response_code(404);
    echo json_encode(["error" => "Recette non trouvée"]);
    exit;
}

try {
    // Mise à jour des infos principales
    $recette->setTitle($_POST['recipe-name'] ?? '');
    $recette->setDescription($_POST['description'] ?? '');
    $recette->setDifficulty((int)($_POST['difficulty'] ?? 1));
    $recette->setPrepTime((int)($_POST['prep-time'] ?? 0));
    if(isset($_POST['userId'])){
        $recette->setIdCreator($_POST['userId']);
    }
    

    // Mise à jour de la recette
    if (!RecipeDAO::update($recette)) {
        throw new Exception("Échec de la mise à jour de la recette");
    }

    // Mise à jour des ingrédients
    RecipeIngredientDAO::deleteAllForRecipe($recipeId);
    if (isset($_POST['ingredients'])) {
        $ingredients = json_decode($_POST['ingredients'], true);
        if (!is_array($ingredients)) throw new Exception("Format des ingrédients invalide");

        foreach ($ingredients as $ing) {
            if (isset($ing['id'], $ing['quantity'], $ing['unit'])) {
                $ri = new RecipeIngredient(
                    $recipeId,
                    (int)$ing['id'],
                    (int)$ing['quantity'],
                    $ing['unit']
                );
                RecipeIngredientDAO::save($ri);
            }
        }
    }

    // Mise à jour des étapes
    StepDAO::deleteAllForRecipe($recipeId);
    if (isset($_POST['steps'])) {
        $steps = json_decode($_POST['steps'], true);
        if (!is_array($steps)) throw new Exception("Format des étapes invalide");

        $i = 1;
        foreach ($steps as $step) {
            if (isset($step['description'])) {
                $etape = new Step($recipeId, $i++, $step['description']);
                StepDAO::save($etape);
            }
        }
    }

    echo json_encode(["success" => true, "message" => "Recette modifiée avec succès."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur lors de la sauvegarde : " . $e->getMessage()]);
}
