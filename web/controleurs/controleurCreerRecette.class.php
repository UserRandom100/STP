<?php

class CreerRecette extends Controleur
{
    // ******************* Constructeur vide
    public function __construct()
    {
        parent::__construct();
    }

    // ******************* Méthode  executerAction  ********************            
    public function executerAction(): string
   {
    return "creation_recette.php";
   } 

}
?>