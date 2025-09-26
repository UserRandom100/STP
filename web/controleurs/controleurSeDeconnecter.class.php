<?php

	include_once("controleurs/controleur.abstract.class.php");
	include_once("modele/DAO/UserDAO.class.php");

	class SeDeconnecter extends  Controleur {
		

		public function __construct() {
			parent::__construct();
		}
		


		public function executerAction():string {


			$this->acteur="visiteur"; 
			unset($_SESSION['utilisateurConnecte']);
			$_SESSION['userId'] = ""; // Enlever l'utilisateur connecté
            $_SESSION['user_id'] = ""; // Enlever l'utilisateur connecté
            $_SESSION['user_email'] = "";
            $_SESSION['user_role'] = "";
           // header("Location: ../Vue/index.php");
            return "deconnexion.php";
		}


		
	}	
	
?>