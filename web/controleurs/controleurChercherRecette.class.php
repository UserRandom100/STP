<?php

class ChercherRecette extends Controleur
{
    // ******************* Constructeur vide
    public function __construct()
    {
        parent::__construct();
    }

    // ******************* Méthode  executerAction  ********************            
    public function executerAction(): string
   {
    return "chercher_recette.php";
   } 

}
?>