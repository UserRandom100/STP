<?php
session_start();

// Inclure la classe User avant d'accéder à $_SESSION
include_once 'modele/user.class.php';

if (isset($_SESSION['utilisateurConnecte'])) {
    // Maintenant vous pouvez accéder à l'objet
    $utilisateur = $_SESSION['utilisateurConnecte'];
    echo $utilisateur->getId(); // Exemple d'accès aux méthodes de l'objet
} else {
    echo "Aucun utilisateur connecté.";
}

?>