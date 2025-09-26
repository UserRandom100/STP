<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swipe Ton Souper - Création de Compte</title>
    <link rel="stylesheet" type="text/css" href="./assets/css/connecter.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/creer_compte.css">
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <script type="text/javascript" src="./assets/js/jquery-3.6.1.min.js"></script>

</head>

<body>
    <header>
        <div class="header-container">
            <?php include("vues/inclusions/logo.inc.php");
            include_once "vues/inclusions/nav_bar.inc.php";
            afficherMenu($controleur);
            ?>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="form-creer-compte-container">
                <h3 class="SousTitre">
                    Créé-toi un compte pour que les autres utilisateurs bénéficient
                    de tes
                    talents culinaires !
                </h3>
                <div class="formulaire">
                    <form action="?action=creerCompte" method="post">
                        <div class="blocFormulaire">
                            <!-- <label for="username">Nom d'utilisateur:</label> -->
                            <input type="text" id="username" name="username" placeholder="Nom de l'utilisateur" required>
                        </div>
                        <div class="blocFormulaire">
                            <!-- <label for="email">Email:</label> -->
                            <input type="email" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="blocFormulaire">
                            <!-- <label for="password">Mot de passe:</label> -->
                            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                        </div>
                        <button type="submit">Créer le Compte</button>
                        <p>Vous avez déjà un compte? <a href="?action=seConnecter">Connectez-vous ici</a></p>
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
    
</body>

</html>