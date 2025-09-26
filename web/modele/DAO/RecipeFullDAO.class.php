<?php
require_once(__DIR__ . "/../recipe.class.php");
require_once(__DIR__ . "/../step.class.php");
require_once(__DIR__ . "/../recipeIngredient.class.php");
require_once(__DIR__ . "/../ingredient.class.php");
require_once(__DIR__ . "/connexionBD.class.php");
require_once(__DIR__ . "/RecipeDAO.class.php");
require_once(__DIR__ . "/RecipeIngredientDAO.class.php");
require_once(__DIR__ . "/StepDAO.class.php");
require_once(__DIR__ . "/IngredientDAO.class.php");

class RecipeFullDAO
{
    /**
     * Récupère une recette complète (recette, ingrédients, étapes) par son ID
     */
    public static function findFullRecipeById(int $id): ?array
    {
        $recipe = RecipeDAO::findById($id);
        if (!$recipe) return null;

        $ingredients = [];
        $ingredientLinks = RecipeIngredientDAO::findByIdRecipe($id);

        foreach ($ingredientLinks as $link) {
            $ingredient = IngredientDAO::findById($link->getIngredientId());
            if ($ingredient) {
                $ingredients[] = [
                    'id' => $ingredient->getId(),
                    'name' => $ingredient->getName(),
                    'quantity' => $link->getQuantity(),
                    'unitOfMeasure' => $link->getUnitOfMeasure()
                ];
            }
        }

        $steps = [];
        $stepList = StepDAO::findByIdRecipe($id);
        foreach ($stepList as $step) {
            $steps[] = [
                'stepNumber' => $step->getStepNumber(),
                'description' => $step->getStepDescription()
            ];
        }

        return [
            'recipe' => $recipe,
            'ingredients' => $ingredients,
            'steps' => $steps
        ];
    }
}
