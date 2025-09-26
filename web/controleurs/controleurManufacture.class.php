<?php

include_once("controleurs/controleurSeConnecter.class.php");
include_once("controleurs/controleurInscription.class.php");
include_once("controleurs/controleurSeDeconnecter.class.php");
include_once("controleurs/controleurCreerRecette.class.php");
include_once("controleurs/controleurCreerRecetteSuite.class.php");
include_once("controleurs/controleurChercherRecette.class.php");
include_once("controleurs/controleurEnregistrer.class.php");
include_once("controleurs/controleuradminPanel.class.php");
include_once("controleurs/controleurModifierRecette.class.php");
include_once("controleurs/controleurInformationRecette.class.php");
include_once("controleurs/controleurCreerRecetteAdmin.class.php");
include_once("controleurs/controleurBienvenue.class.php");


class ManufactureControleur
{
	//  Méthode qui crée une instance du controleur associé à l'action
	//  et le retourne
	public static function creerControleur($action): Controleur
	{
		$controleur = null;
		if ($action == "seConnecter") {
			$controleur = new SeConnecter();
		} else if ($action == "creerCompte") {
			$controleur = new Inscription();
		} else if ($action == "seDeconnecter") {
			$controleur = new SeDeconnecter();
		} else if ($action == "creerRecette") {
			$controleur = new CreerRecette();
		} else if ($action == "creerRecetteSuite") {
			$controleur = new CreerRecetteSuite();
		} else if ($action == "creerRecetteAdmin"){
			$controleur = new CreerRecetteAdmin();
		} else if ($action == "chercherRecette") {
			$controleur = new ChercherRecette();
		} else if ($action == "voirMesRecettes"){
            $controleur = new RecettesEnregistrer();
		} else if($action == "adminPanel"){
			$controleur = new adminPanel;
		} else if ($action == "modifierRecette"){
			$controleur = new ModifierRecette();
		} else if($action == "informationRecette"){
			$controleur = new InformationRecette();
		} else if($action == "bienvenue"){
			$controleur = new Bienvenue();
		}else if ($action == "sauvegarderModificationRecette") {
            $controleur = new ModifierRecette();
        } else {
			$controleur = new SeConnecter();
		}

		return $controleur;
	}
}
