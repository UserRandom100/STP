<?php

class Bienvenue extends Controleur
{
    // ******************* Constructeur vide
    public function __construct()
    {
        parent::__construct();
    }

    // ******************* Méthode  executerAction  ********************            
    public function executerAction(): string
   {
    return "bienvenue.php";
   } 

}
?>