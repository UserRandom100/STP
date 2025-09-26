<?php

include_once("modele/DAO/RecipeDAO.class.php");
//gestion des case recette dans le menue des recettes enregistrer

function afficherRecette()
{
	$utilisateur = $_SESSION['utilisateurConnecte'];
        $listRecettesEnregistrer = RecipeDAO::findByUser($utilisateur->getId());
        if(is_array($listRecettesEnregistrer)){
            foreach($listRecettesEnregistrer as $recettes){

                $dificulterEnInt = $recettes['difficulty'];
                $dificulterEnString = "$dificulterEnInt";
                if($dificulterEnInt == 2){$dificulterEnString = "intermedière";}
                else if($dificulterEnInt == 1){$dificulterEnString = "facile";}
                else if($dificulterEnInt == 3){$dificulterEnString = "difficile";}
                else if($dificulterEnInt == 0){$dificulterEnString = "Non-définie";}

                echo("
                <div class=\"case_recette\">
                    <div class=\"ligne\"> ".$recettes['title']."</div>
                    <div class=\"ligne\"> ".$recettes['description']."</div>
                    <div class=\"ligne\"> ".$dificulterEnString." </div>
                    <div class=\"ligne\"> ".$recettes['prepTime']." minutes</div>
                    <form action=\"?action=informationRecette\" method=\"post\">
                        <input type=\"hidden\" name=\"recipeId\" value=\"" . $recettes['id'] . "\">
                        <button class =\"BtDetail\" type=\"submit\">Detail</button>
                    </form>
                </div>
                ");
            }
        }else{
            echo("<div class=\"ligne\"> ".$listRecettesEnregistrer."</div>");
        }
}
?>