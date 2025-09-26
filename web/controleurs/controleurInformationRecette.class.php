<?php

	include_once("controleurs/controleur.abstract.class.php");
	include_once("modele/DAO/UserDAO.class.php");
    include_once("modele/DAO/RecipeDAO.class.php");
    include_once("modele/recipe.class.php");

	class InformationRecette extends  Controleur {
		

		public function __construct() {
			parent::__construct();
		}
		


		public function executerAction():string {

            if(isset($_POST['recipeId'])&& !empty($_POST['recipeId'])){
			
            $recette = RecipeDAO::findById($_POST["recipeId"]);
           
            $id = $recette->getId();
            $_GET['id'] = $id;
            return ("information_recette_creer.php");
            }
            return "recettesEnregistrer.php";
		}


		
	}	
	
?>