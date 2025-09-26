<?php

// include_once "vues/inclusions/nav_bar.inc.php";
// afficherMenu($controleur);

function afficherMenu($controleur)
{
    $menu = "<nav>
        <ul>";

    $typeActeur = $controleur->getActeur(); // Obtenir le type d'utilisateur

    if ($typeActeur === "visiteur") {
        $menu .= "<li class='option_active'><a href='?action=seConnecter'>Se connecter</a></li>";
    } else {
        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['utilisateurConnecte']) && $_SESSION['utilisateurConnecte'] instanceof User) {
            $utilisateurConnecte = $_SESSION['utilisateurConnecte'];
            $nom = htmlspecialchars($utilisateurConnecte->getName());
            $nomComplet = $nom;

            $menu .= "<li class='nav-nom-connect'> Chef " . $nomComplet . "</li>";

            $menu .= " <li class='active'>
                        <a href='?action=creerRecette'>Créer</a>
                      </li>

                      <li>
                        <a href='?action=chercherRecette'>Découvrir</a>
                      </li>
                      <li>
                        <a href='?action=voirMesRecettes'>Mes recettes</a>
                      </li>";

            
            if ($controleur->isAdmin()) {
              $menu .= "<li><a href='?action=adminPanel'>Panneau d'administration</a></li>";
              // $menu .= "<li><a href='?action=creerCompte'>Créer compte OnlyChef (commande admin)</a></li>";
          }
        }
        $menu .= "<li><a href='?action=seDeconnecter'>Se déconnecter</a></li>";
    }

    $menu .= "</ul>
      </nav>";

    echo $menu;
}
?>
