<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swipe Ton Souper - Connecter</title>
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/creer_recette.css">
    <script type="text/javascript" src="./assets/js/jquery-3.6.1.min.js"></script>
    <style>
            
            .recipe-card {
      background-color: #fff;
      border: 50px solid rgba(255, 132, 132, 0.32); /* noir à 50% */
      border-radius: 10px;
      padding: 20px;
      max-width: 600px;
      margin: 0 auto;
      box-shadow: 2px 2px 10px rgba(0,0,0,0.2);
    }
    .recipe-card h2 {
      background-color: #FF908D;
      color: white;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      margin-top: 0;
    }
    .info-line {
      margin-bottom: 15px;
      
    }
    .info-label {
      font-weight: bold;
      margin-bottom: 5px;
      display: block;
      color:rgb(240, 153, 23);
    }
    #BtModifierDetail{
        /*----couleurs----*/
        background-color: #FFc0cD;

        /*----forme----*/ 
        border-radius: 10px;
        width: 120px;
        height: 40px;

        /* transition */
        transition: transform 0.1s ease;
        transform-origin: center center;
    } 
    #BtModifierDetail:hover{
        /*----couleurs----*/
        background-color:rgb(243, 84, 150);

        /*changement grandeur*/ 
        transform: scale(1.1);
    }
    
    
  
        </style>
</head>

<body>
    <header>

        <div class="header-container">
            <?php include("vues/inclusions/logo.inc.php");
            include_once "vues/inclusions/nav_bar.inc.php";
            afficherMenu($controleur);
            //Verifiaction de l'URL pour un id de recette
            $idRecette = $_GET['id'];
            // Si l'idRecette est valide, on charge la recette
            $donneesRecette = null;
            if($idRecette != NULL && $idRecette != false) {
                // Fonction pour récupérer les données de la recette
                $donneesRecette = RecipeDAO::findById($idRecette);
            }
            ?>
        </div>

        
    </header>

    <main>
        <div class="container">
            
            <?php
                include_once "inclusions/InformationRecette.inc.php";
                afficherRecetteDetail($idRecette);
            ?>
            
        </div>

        <div class="boulettes boulette1"></div>
        <div class="boulettes boulette2"></div>
        <div class="boulettes boulette3"></div>
        <div class="boulettes boulette4"></div>
        <div class="boulettes boulette5"></div>
    </main>


</body>

</html>