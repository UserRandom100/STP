<?php
include_once "controleurs/controleurManufacture.class.php";



//Obtenir le bon controleur
$action = isset($_GET['action']) ? $_GET['action'] : "";

//Créer une instance du contrôleur adapté 
$controleur = ManufactureControleur::creerControleur($action);

// Exécuter l'action et obtenir le nom de la vue
$nomVue = $controleur->executerAction();

if (!isset($nomVue) || empty($nomVue)) {
    die("Erreur: \$nomVue n'est pas défini ou est vide après l'exécution de l'action!");
}

// Vérifie si l'extension .php est déjà là
if (!str_ends_with($nomVue, '.php')) {
    $nomVue .= '.php';
}

$pathVue = "vues/" . $nomVue;

if (!file_exists($pathVue)) {
    die("Vue introuvable : $pathVue");
}

include_once($pathVue);



?>