<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afficher la Recette</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        .recipe-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }

        .recipe-info {
            margin-bottom: 20px;
        }

        .ingredients,
        .steps {
            margin-top: 20px;
        }

        .ingredient,
        .step {
            margin-bottom: 10px;
        }

        .step {
            padding-left: 20px;
        }

        .ingredient p,
        .step p {
            margin: 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1 id="recipe-title">Titre de la recette</h1>
        <!-- <img id="recipe-image" class="recipe-image" src="" alt="Image de la recette"> -->
        <!-- <img src="http://localhost/SwipeTonSouper_MySQL/web/uploads/images/23/1743744386_hamburger.png" alt="Image de recette" /> -->


        <!-- IMAGE BINAIRE QUI EST TRANSFORMÉ EN BASE64 !!! -->
         <!-- Image de recette id = 32  -->
<img src="data:image/jpeg;base64,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" alt="Recipe Image" />        
        
        
        <div class="recipe-info">
            <p><strong>Description :</strong> <span id="recipe-description">Description de la recette</span></p>
            <p><strong>Difficulté :</strong> <span id="recipe-difficulty">Facile</span></p>
            <p><strong>Temps de préparation :</strong> <span id="recipe-prepTime">10 min</span></p>
        </div>

        <div class="ingredients">
            <h3>Ingrédients :</h3>
            <ul id="ingredient-list">
                <!-- Liste des ingrédients -->
            </ul>
        </div>

        <div class="steps">
            <h3>Étapes :</h3>
            <ol id="step-list">
                <!-- Liste des étapes -->
            </ol>
        </div>
    </div>

    <script>
        // Simuler la réponse JSON de l'API
        const responseData = {
            "recipeData": {
                "description": "123",
                "difficulty": "2",
                "image": "C:\\xampp\\htdocs\\SwipeTonSouper_MySQL\\web\\api_tempo\\recipe/../../uploads/images/23/1743744386_hamburger.png",
                "ingredients": [{
                    "numero": 1,
                    "unite": "2",
                    "ingredient": "Pommes",
                    "unitOfMeasure": "unités"
                }],
                "name": "rec9",
                "prepTime": "1",
                "steps": [{
                        "numero": 1,
                        "description": "123"
                    },
                    {
                        "numero": 2,
                        "description": "89"
                    }
                ],
                "recipeId": 24
            },
            "message": "Recette enregistrée avec succès.",
            "recipeId": 24
        };

        // Fonction pour afficher la recette
        function displayRecipe(recipe) {
            // Titre de la recette
            document.getElementById('recipe-title').textContent = recipe.name;

            // Description
            document.getElementById('recipe-description').textContent = recipe.description;

            // Difficulté (1 = Facile, 2 = Difficile, vous pouvez l'adapter)
            document.getElementById('recipe-difficulty').textContent = recipe.difficulty === "1" ? 'Facile' : 'Difficile';

            // Temps de préparation
            document.getElementById('recipe-prepTime').textContent = recipe.prepTime + ' min';

            // Affichage de l'image
            // const imagePath = recipe.image.replace(/\\/g, '/'); // Remplacer les anti-slash par des slashs
            // document.getElementById('recipe-image').src = imagePath;

            // Remplacer le chemin local par un chemin relatif ou absolu pour que l'image soit accessible via HTTP

            // const imagePath = "/SwipeTonSouper_MySQL/web/uploads/images/" + userId + "/1743744386_hamburger.png";
            const imagePath = "/SwipeTonSouper_MySQL/web/uploads/images/23/1743744386_hamburger.png"; // Chemin relatif
            document.getElementById('recipe-image').src = imagePath;


            // Affichage des ingrédients
            const ingredientList = document.getElementById('ingredient-list');
            recipe.ingredients.forEach(ingredient => {
                const li = document.createElement('li');
                li.classList.add('ingredient');
                li.innerHTML = `<p>${ingredient.ingredient} (${ingredient.unite} ${ingredient.unitOfMeasure})</p>`;
                ingredientList.appendChild(li);
            });

            // Affichage des étapes
            const stepList = document.getElementById('step-list');
            recipe.steps.forEach(step => {
                const li = document.createElement('li');
                li.classList.add('step');
                li.innerHTML = `<p>Étape ${step.numero}: ${step.description}</p>`;
                stepList.appendChild(li);
            });
        }

        // Afficher la recette après avoir récupéré les données
        displayRecipe(responseData.recipeData);
    </script>

</body>

</html>