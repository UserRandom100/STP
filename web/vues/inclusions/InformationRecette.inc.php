<?php

include_once("modele/DAO/RecipeDAO.class.php");
include_once("modele/DAO/RecipeIngredientDAO.class.php");
include_once("modele/DAO/IngredientDAO.class.php");
include_once("modele/DAO/StepDAO.class.php");
//gestion des case recette dans le menue des recettes enregistrer

function afficherRecetteDetail($id)
{
    $recette = RecipeDAO::findById($id);
        
    echo("<div class=\"recipe-card\">");

    //info de base
    echo("<h2>Informations de la recette</h2>
        <div class=\"info-line\">
            <span class=\"info-label\">Nom de la recette</span>
            <span class=\"info-value\">".$recette->getTitle()."</span>
        </div>
        <div class=\"info-line\">
            <span class=\"info-label\">Description</span>
            <span class=\"info-value\">".$recette->getDescription()."</span>
        </div>
        <div class=\"info-line\">
            <span class=\"info-label\">Difficulté</span>
            <span class=\"info-value\">".$recette->getDifficulty()."</span>
        </div>
        <div class=\"info-line\">
            <span class=\"info-label\">Temps de préparation</span>
            <span class=\"info-value\">".$recette->getPrepTime()."</span>
        </div>");

    $ingredients = RecipeIngredientDAO::findByIdRecipe($recette->getId());

    echo("<div class=\"info-line\">
                    <span class=\"info-label\">Ingrédients</span>
                    <span class=\"info-value\">
                        <ul>");
                            
    foreach($ingredients as $v){
        $ingrediant = IngredientDAO::findById($v->getIngredientId());
        echo("<li>".
                $ingrediant->getName()." ".$v->getQuantity() ." ".$v->getUnitOfMeasure().
            "</li>");
    }

                        
    echo ("</ul>
        </span>
        </div>");

    echo("<div class=\"info-line\">
        <span class=\"info-label\">Étapes</span>
        <span class=\"info-value\">
            <ol>");
    
    $steps = StepDAO::findByIdRecipe($recette->getId());
    foreach($steps as $v){
        
        echo("<li>".
                " ".$v->getStepDescription().
            "</li>");
    }

     echo("</ol>
            </span>
            </div>
    <form action=\"?action=modifierRecette&&idRecette=".$id."\" method=\"post\">
        <button id=\"BtModifierDetail\" type=\"submit\">modifierRecette</button>
    </form>");

    echo("</div>");
        
    
}
?>