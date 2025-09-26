<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="assets/js/admin.js"></script>
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <script type="text/javascript" src="assets/js/jquery-3.6.1.min.js"></script>
  <script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
    <title>Panneau d'Administration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #B0E0D3; /* bleu_background */
            margin: 0;
            padding: 50px;
        }
        h1 {
            background-color: #e0aedcb8; /* rose_header */
            color: #6BBDC6; /* bleu */
            padding: 20px;
            border-radius: 10px;
        }
        .container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 20px 40px;
            font-size: 20px;
            text-decoration: none;
            background-color: #F89E32; /* orange */
            color: white;
            border-radius: 10px;
            transition: background 0.3s, transform 0.2s;
            border: 3px solid #e0aedcb8; /* rose_bordure */
        }
        .btn:hover {
            background-color: #e7534e98; /* rose button_hover */
            transform: scale(1.05);
        }
        .btn:active {
            background-color: #ff918d98; /* rose_button_active */
        }
    </style>
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
    <h1>Panneau d'Administration</h1>
    <div class="container">
        <form id="form-modif-admin" >

            <div class="titre-nom-recipe">Nom de la recette</div>
            <input class="form-text" type="text" id="recipe-name" name="recipe-name" placeholder="Nom de la recette..." required>

            <div class="titre-description-recipe">Description de la recette</div>
            <textarea class="form-text" id="recipe-description" name="recipe-description" placeholder="description de la recette..." required></textarea>

            <div class="titre-difficulty-recipe">Difficulter de la recette</div>
            <input class="form-text" type="number" id="recipe-difficulty" name="recipe-difficulty" placeholder="difficulter de la recette..." required>

            <div class="titre-prepTime-recipe">Temps de préparation de la recette</div>
            <input class="form-text" type="number" id="recipe-prepTime" name="prepTime-difficulty" placeholder="temp de préparation de la recette..." required>
            <br>
            <div class="titre-prepTime-recipe">Personne Créatrice</div>
            
            <select name="menu-deroulant" id="menu-deroulant">
                <script>
                $.ajax({
                    url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/user/getAllUser.php',
                    method: 'GET',
                    dataType: 'json', // Optionnel : si vous attendez du JSON
                    success: function(response) {

                        
                        let tabObjet = response['user'];
                        tabObjet.forEach(function(object, index) {

                        $("#menu-deroulant").append(
                        ' <option value="'+object['id']+'">'+object['name']+'</option>'
                        );
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Erreur:', textStatus, errorThrown);
                    }
                });

                </script>

            </select>
            <br>
            <div class="form-group">
            <label for="recipe-image">Photo de la recette</label>
            <br>
            <input class="form-control" type="file" id="recipe-image" name="recipe-image" accept="image/*">
            </div>
            <br>

            <div id = "conteneurIngrediants">


            </div>
            <button type="button" id="btn-add-ingredient">
                Ajouter ingrédient
            </button>
            <script>
                $('#btn-add-ingredient').on('click', function() {
                    var idIng   = $('#menu-deroulant-ingrediant').val();
                    var quantite = $('#zone_de_text_quantiter').val();
                    ajouterIngredient(idIng, quantite);
                });
            </script>
            <button type="button" id="btn-suprimer-ingredient">
                suprimer ingrédient
            </button>
            <script>
                $('#btn-suprimer-ingredient').on('click', function() {
                    $('#conteneurIngrediants').children().last().remove();
                });
            </script>
            <select name="menu-deroulant-ingrediant" id="menu-deroulant-ingrediant">
                <script>
                     $.ajax({
                            url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/ingredient/rechercher_ingredients.php',
                            method: 'GET',
                            dataType: 'json',
                            data:{query:"%"},
                            success: function(response) {
                                    response.forEach(item => {
                                        $('#menu-deroulant-ingrediant').append(
                                            '<option value="' + item.name + '">' + item.name +' '+item.unitOfMeasure+ '</option>'
                                        );
                                 }); 
                               
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log('Erreur:', textStatus, errorThrown);
                            }
                     });
                </script>
            </select>
            <input type="number" id="zone_de_text_quantiter">
            <br>

            <div id="conteneur-steps">
                <div id="conteneur-step1" class="conteneur-step">
                    <div class="titre-step-recipe">Etape 1</div>
                    <input class="input_steps" type="text" id="step1" name="step1" placeholder="description de l'etape" required>
                </div>
            </div>
            <button type="button" id="button-add-step" onclick="ajouterEtape()">Ajouter</button>
            <br>
            <button type="button" id="btn-suprimer-step">
                suprimer étape
            </button>
            <script>
                $('#btn-suprimer-step').on('click', function() {
                    $('#conteneur-steps').children().last().remove();
                });
            </script>
            <br>
            <button type="button" id="button-send" onclick="ajouterRecette()">Enregistrer</button>
        </form>
    </div>
</body>
</html>
