<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swipe Ton Souper - Connecter</title>
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <script type="text/javascript" src="./assets/js/jquery-3.6.1.min.js"></script>
    <style>

            .titreMenuDuChef{
                
                /* --- text--- */ 
                text-align: center;     
                font-size: 40px;   
                font-style: italic;
                font-family: 'Charm', sans-serif;

                /* --- couleurs--- */ 
                background-color: #F2E6E4;  /* Fond blanc du conteneur */
                color: #FF717D;

                /* --- placement--- */ 
                margin: 10px;
                padding: 20px; 
            }
            .container {

                /* --- placement--- */
                display: flex;  
                flex-wrap: wrap;
                justify-content: center; 
                align-items: center;     
                padding: 20px;           
                margin: 10px;

                 /* --- couleurs--- */ 
                background-color: #F2E6E4;  /* Fond blanc du conteneur */
                
            }

            .case_recette {

                /* --- placement--- */
                padding: 20px;            
                margin: 10px;               
                box-sizing: border-box;   
                border-radius: 10px;
                min-width: 400px;  /* largeur minimale */
                max-width: 600px;  /* largeur maximale */
                flex: 1 1 auto;    /* permet à l’item de grandir et rapetisser entre ces bornes */
                text-align: center;

                /* --- couleurs--- */ 
                background-color: #FF919D; 
                border: solid #cfa1ad;
            }

            .ligne{
                margin: 10px; 
                padding: 5px; 
                border-radius: 10px;
                background-color: #fFc17D; 
                text-align: center;
                box-shadow:3px 3px 3px #dFa16D;

                /* transition */
                transition: transform 0.1s ease;
                transform-origin: center center;
            }

            .ligne:hover{
                transform: scale(1.1);
            }
            .BtDetail{
                
                /*----couleurs----*/
                background-color: #FFc0cD;

                /*----forme----*/ 
                border-radius: 10px;
                width: 100px;
                height: 30px;
            }

        </style>
</head>

<body>
    <header>
        <div class="header-container">
            <?php include("inclusions/logo.inc.php"); 
             include_once "inclusions/nav_bar.inc.php";

            afficherMenu($controleur);
            ?>
        </div>
    </header>

    <main>
    <div class="titreMenuDuChef">--------------------------Menu Du Chef--------------------------</div>
        <div class="container">
        
                <?php
                    include_once "inclusions/case_recette.inc.php";
                    afficherRecette();
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