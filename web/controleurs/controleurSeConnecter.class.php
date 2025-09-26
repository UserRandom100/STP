<?php


include_once("controleurs/controleur.abstract.class.php");
include_once(__DIR__ . "/../modele/DAO/UserDAO.class.php");


class SeConnecter extends Controleur
{

    private $tabProduits;


    public function __construct()
    {
        parent::__construct();
        $this->tabProduits = array();
    }


    public function getTabProduits(): array
    {
        return $this->tabProduits;
    }


    public function executerAction(): string
    {
        // Vérifier si l'utilisateur est déjà connecté
        if ($this->acteur == "utilisateur") {
            array_push($this->messagesErreur, "Cher chef, vous êtes déjà authentifié-e");
            return "bienvenue.php";
        }

        // Vérifier si les informations POST sont présentes
        if (isset($_POST['connexion-email']) && isset($_POST['connexion-password'])) {

            //filter_var(..., FILTER_SANITIZE_EMAIL) → Nettoie un email.
            $userEmail =  filter_var($_POST['connexion-email'], FILTER_SANITIZE_EMAIL);

            //trim() → Supprime les espaces avant/après la saisie.
            $userMotDePasse = trim($_POST['connexion-password']); 
            
                            
            $utilisateur = UserDAO::findByEmail($userEmail);
            
            // Vérification de l'existence de l'utilisateur
            if ($utilisateur == null) {
                array_push($this->messagesErreur, "Pas d'utilisateur trouvé. Créez-vous un compte !");
                return "connexion.php";

            } elseif ($utilisateur->getId() == 1) {
            $this->acteur = "Admin";
            $_SESSION['utilisateurConnecte'] = $utilisateur;
            $_SESSION['userId'] = $utilisateur->getId();

            return "bienvenue.php";
            }
            elseif(password_verify($userMotDePasse, $utilisateur->getPassword())) {
                $_SESSION['utilisateurConnecte'] = $utilisateur;
                $_SESSION['userId'] = $utilisateur->getId();
                
                $this->acteur = "utilisateur";
                return "bienvenue.php";
            }
            else {
                array_push($this->messagesErreur, "Mot de passe incorrect. Réessayez !");
                return "connexion.php";
            }
        }

        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$message');</script>";
        }
        
        return "connexion.php";
    }
}
