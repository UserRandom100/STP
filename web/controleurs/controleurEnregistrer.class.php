<?php 

class RecettesEnregistrer extends Controleur{

    public function __construct(){
        parent::__construct();
        }
        public function executerAction()
        {
            /*$utilisateur = $_SESSION['utilisateurConnecte'];

            //$listRecettesEnregistrer = recettesDAO::getAllEnregistrer($utilisateur->getId);
            $listRecettesEnregistrer = [["id"=>"1", "titre"=>"spagatie", "description"=>"un plat italien", "difficulter"=>"2", "tempsdepreparation"=>20]];
            foreach($listRecettesEnregistrer as $recettes){
                echo("
                <div class=\"case_recette\">
                    <div class=\"ligne\"> ".$recettes['titre']."</div>
                    <div class=\"ligne\"> ".$recettes['description']."</div>
                    <div class=\"ligne\"> ".$recettes['difficulter']."</div>
                    <div class=\"ligne\"> ".$recettes['tempsdepreparation']."</div>
                </div>
                ");
            }*/
            return "recettesEnregistrer.php";
        }
}
?>