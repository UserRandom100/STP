<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panneau d'Administration</title>
    
    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="assets/js/admin.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #B0E0D3;
            margin: 0;
            padding: 50px;
        }

        h1 {
            background-color: #e0aedcb8;
            color: #6BBDC6;
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
            background-color: #F89E32;
            color: white;
            border-radius: 10px;
            transition: background 0.3s, transform 0.2s;
            border: 3px solid #e0aedcb8;
        }

        .btn:hover {
            background-color: #e7534e98;
            transform: scale(1.05);
        }

        .btn:active {
            background-color: #ff918d98;
        }

        #recipe-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .recipe-box {
            width: 300px;
            background-color: #ffffffdd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            text-align: left;
        }


        .recipe-box:hover {
            transform: scale(1.05);
        }

        .delete-btn {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        .modify-btn {
            margin-top: 10px;
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .modify-btn:hover {
            background-color: #2980b9;
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
    <main>
        <h1>Panneau d'Administration</h1>
        <div class="container">
            <a href='?action=creerRecetteAdmin' class="btn">Créer une recette</a>
        </div>

        <div>
            <p> modification des recettes </p>
        </div>
        <div>
            <p>⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩⇩</p>
        </div>


        <div id="recipe-container"></div>
        <button id="load-more">Charger plus</button>

    </main>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: "../web/api_tempo/recipe/toutes_recettes.php",
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.recipes) {
                        $("#recipeContainer").empty();
                        response.recipes.forEach(recipe => {
                            let recipeBox = `
                <div class='recipe-box' id="recette${recipe.id}">
                    <h3>${recipe.title}</h3>
                    <p><strong>Description:</strong> ${recipe.description}</p>
                    <p><strong>Difficulté:</strong> ${recipe.difficulty}</p>
                    <p><strong>Temps de préparation:</strong> ${recipe.prepTime} min</p>
                    <button class="delete-btn" data-id="${recipe.id}">Supprimer</button>
                    <button class="modify-btn" data-id="${recipe.id}">Modifier</button>
                </div>
            `;
                            $("#recipeContainer").append(recipeBox);
                        });
                    } else {
                        $("#recipeContainer").html("<p>Aucune recette trouvée.</p>");
                    }
                }
                ,


            });
        });

        $(document).on('submit', '#form-modif-admin', function (e) {
            e.preventDefault();  // pour empêcher la soumission normale du formulaire
            ajouterRecette();

        });

        $(document).on("click", ".delete-btn", function (e) {
            e.stopPropagation();
            const recipeId = $(this).data("id");

            if (confirm("Voulez-vous vraiment supprimer cette recette ?")) {
                $.ajax({
                    url: "../web/api_tempo/recipe/suprimer_recette_par_id.php",
                    type: "DELETE",
                    contentType: "application/json",
                    data: JSON.stringify({ id: recipeId }),
                    success: function (response) {
                        alert(response.message);
                        $(`#recette${recipeId}`).remove();
                    },
                    error: function (xhr) {
                        alert("Erreur lors de la suppression de la recette.");
                        console.error(xhr.responseText);
                    }
                });
            }
        });

        $(document).on("click", ".modify-btn", function (e) {
            e.stopPropagation(); // empêche le bloc principal d’être cliqué
            const recipeId = $(this).data("id");
            afficherMenuModifier(recipeId);
        });


    </script>

    <script>
        let offset = 0;
        const limit = 10;

        function chargerRecettes() {
            $.get(`./api_tempo/recipe/toutes_recettes.php?limit=${limit}&offset=${offset}`, function (data) {
                const recettes = JSON.parse(data);

                if (recettes.length === 0) {
                    $('#load-more').hide(); // Plus rien à charger
                    return;
                }

                recettes.forEach(recette => {
                    const bloc = `
                <div class="recipe-box">
                    <h3>${recette.title}</h3>
                    <p>${recette.description.substring(0, 150)}...</p>
                    <button onclick="location.href='?action=modifierRecette&id=${recette.id}'">Modifier</button>
                    <button onclick="supprimerRecette(${recette.id})">Supprimer</button>
                </div>
            `;
                    $('#recipe-container').append(bloc);
                });

                offset += limit;
            });
        }

        $('#load-more').on('click', chargerRecettes);


        $(document).ready(chargerRecettes);
    </script>


</body>

</html>