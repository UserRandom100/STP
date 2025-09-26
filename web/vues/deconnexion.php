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
            include("vues/inclusions/erreur.inc.php");
            ?>
        </div>


    </header>
    <main>
        <div class="container">
            <div class="form-container">
                <h3 class="SousTitre">
                    <?php
                    // Appel de la fonction pour afficher les erreurs
                    afficherErreurs($controleur->getMessagesErreur());
                    ?>
                    Au revoir avec <span style="text-transform: uppercase">swipe ton souper</span>, Chef !
                </h3>
                <div class="formulaire">
                    Vous êtes déconnecté(e) de votre compte.
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

    <style>
        .fade-out {
            opacity: 0;
            transition: opacity 1s ease;
        }
    </style>

    <script>
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.body.classList.add('fade-out');
                setTimeout(function() {
                    window.location.href = "?action=seConnecter";
                }, 1000); // attend que le fade-out se termine
            }, 1200);
        });
    </script>

</body>

</html>