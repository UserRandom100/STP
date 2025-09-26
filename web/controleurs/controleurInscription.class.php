<?php

// *****************************************************************************************
// Description   : Contrôleur spécifique pour l'action de création de compte utilisateur
// *****************************************************************************************

include_once("controleurs/controleur.abstract.class.php");
include_once("modele/DAO/UserDAO.class.php");
include_once("modele/role.class.php");
include_once("modele/user.class.php");

class Inscription extends Controleur
{
    // ******************* Attributs
    private $tabProduits;

    // ******************* Constructeur vide
    public function __construct()
    {
        parent::__construct();
        $this->tabProduits = array();
    }

    // ******************* Accesseurs
    public function getTabProduits(): array
    {
        return $this->tabProduits;
    }

    // Méthode pour vérifier si l'utilisateur est admin
  

    // ******************* Méthode executerAction
    public function executerAction(): string
    {
        // Vérifiez si le formulaire de création de compte est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Déterminer le rôle en fonction des permissions
            if ($this->isAdmin() && isset($_POST['role'])) {
                // Si l'utilisateur est admin, utiliser le rôle fourni dans le formulaire
                $role = (int)$_POST['role'];
            } else {
                // Sinon, attribuer le rôle "Client" par défaut
                $role = 2; // 2 = Utilisateur
            }

            if (isset($_POST['email']) && isset($_POST['password'])) {

                //trim() → Supprime les espaces avant/après la saisie.
                $userNom =  trim($_POST['username']);

                //filter_var(..., FILTER_SANITIZE_EMAIL) → Nettoie un email.
                $userEmail =  filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

                //trim() → Supprime les espaces avant/après la saisie.
                $userMotDePasse = trim($_POST['password']);
            } else {
                return "creation_compte.php";
            }

            // Création de l'utilisateur
            $utilisateur = new User(
                null,
                $userNom, 
                $userEmail, 
                $userMotDePasse,
                $role
            );

             //on hash le mot de passe de $usilisateur
            $utilisateur->hashPassword();

            if(!UserDAO::existsByEmail($utilisateur->getEmail())){
            
                //si non on essaye d'ajouter l'utilisateur
                if(UserDAO::save($utilisateur)){
                    $_SESSION['user_id'] = $utilisateur->getId(); // Stocker l'utilisateur connecté
                    $_SESSION['user_email'] = $utilisateur->getEmail();
                    return "creation_recette.php";
                
                //si l'ajoue echoue on envoie une erreur
                }else{
                    array_push($this->messagesErreur, "une erreur ces produite lors de la création du compte");
                    return "connexion.php";
                }
            }
            else{
                array_push($this->messagesErreur, "email existe deja");
                return "connexion.php";
            }
        }
        
        // Si le formulaire n'est pas soumis, afficher la page de création de compte
        return "creation_compte.php";
    }
}
