<?php
// *****************************************************************************************
// Description   : Contrôleur spécifique pour l'action de voirIngrédients qui s'occupe de gérer
//                 l'affichage de l'ensemble des ingrédients
// *****************************************************************************************
include_once("controleurs/controleur.abstract.class.php");
include_once("modele/DAO/IngredientDAO.class.php");

class VoirIngrédients extends Controleur {
    // ******************* Attributs
    private $tabIngrédients;
    
    // ******************* Constructeur vide
    public function __construct() {
        parent::__construct();
        $this->tabIngrédients = array();
    }
    
    // ******************* Accesseurs
    public function getTabIngrédients(): array {
        return $this->tabIngrédients;
    }

    // ******************* Méthode exécuter action
    public function executerAction(): string {
        // Vérifie si la clé 'description' existe et n'est pas vide dans POST
        if (isset($_POST['description']) && !empty($_POST['description'])) {
            // Recherche d'ingrédients dont le nom contient le terme de recherche
            $this->tabIngrédients = IngredientDAO::findByDescription($_POST['description']);
        } else {
            // Sinon, récupère tous les ingrédients
            $this->tabIngrédients = IngredientDAO::findAll();    
        }

        // Retourne la vue correspondante pour afficher les ingrédients
        return "ingredients.php";
    }
}
?>
