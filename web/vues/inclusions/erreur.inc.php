<?php
//gestion des erreurs pour la connexion
function afficherErreurs($tabMessages)
{
	if (count($tabMessages) > 0) {
		echo "<div class='erreur'>";
		echo "<ul>";
		foreach ($tabMessages as $message) {
			echo "<li>$message</li>";
		}
		echo "</ul>";
		echo "</div>";
	}
}
?>