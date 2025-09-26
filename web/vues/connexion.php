<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swipe Ton Souper - Connecter</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/connecter.css">
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <script type="text/javascript" src="./assets/js/jquery-3.6.1.min.js"></script>

</head>

<body>
    <header>
        <div class="header-container">
            <?php include("vues/inclusions/logo.inc.php");
            //include("vues/inclusions/erreur.inc.php");
            ?>
        </div>
    </header>

    <div class="flag-container">
        <div class="flag"></div>
    </div>

    <main>
        <div class="container">
            <div class="form-container">
                <h3 class="SousTitre">
                    Bienvenue sur <span style="text-transform: uppercase">swipe ton souper</span>, Chef !
                </h3>

                <?php
                include("vues/inclusions/erreur.inc.php");
                // Appel de la fonction pour afficher les erreurs
                afficherErreurs($controleur->getMessagesErreur());
                ?>

                <div class="formulaire">
                    <form action="?action=seConnecter" method="post">
                        <div class="blocFormulaire">
                            <!-- <label for="email">Email:</label> -->
                            <input type="email" id="connexion-email" name="connexion-email" placeholder="Email" required>
                        </div>
                        <div class="blocFormulaire">
                            <!-- <label for="password">Mot de passe:</label> -->
                            <input type="password" id="connexion-password" name="connexion-password" placeholder="Mot de passe" required>
                        </div>
                        <button type="submit">Se connecter</button>
                        <p>Vous n'avez pas de compte? <a href="?action=creerCompte" class="transition-link-flag">Créez un compte ici</a></p>
                    </form>
                </div>
            </div>


        </div>

        <div class="boulettes boulette1"></div>
        <div class="boulettes boulette2"></div>
        <div class="boulettes boulette3"></div>
        <div class="boulettes boulette4"></div>
        <div class="boulettes boulette5"></div>
    </main>

    <footer>
        <?php include("vues/inclusions/pied.inc.php"); ?>
    </footer>

    <script src="./assets/js/transition-flag.js"></script>
</body>

</html>