<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Recettes</title>

    <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/connecter.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/creer_recette.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/board.css">
    <link rel="stylesheet" type="text/css" href="./assets/css/chercher_recette_decouvrir.css">
    <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <script type="text/javascript" src="./assets/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="./assets/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="./assets/js/additional-methods.min.js"></script>
    <script type="text/javascript" src="./assets/js/messages_fr.min.js"></script>


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
            <div class="form-container">
                <h3 class="SousTitre" style="font-size: 15px;">
                    Bonjour, <br><br>
                    La langue française n'est pas encore supportée pour la recherche de recettes. <br>
                    Merci de saisir les ingrédients en anglais.<br>
                    Cordialement,<br><br>
                    Équipe SwipeTonSouper
                </h3>

                <h1>Recherche de recettes</h1>
                <form id="recipeForm">
                    <label for="query">Écrire les ingrédients en anglais :</label>
                    <input type="text" id="query" name="query" placeholder="Ex. Chicken rice">
                    <button type="submit">Rechercher</button>
                </form>

                <div id="recipe"></div>


            </div>
        </div>
    </main>

    <script>
        var tabJsonRecipes = []; // Tableau pour stocker les recettes JSON
        // Cibler le formulaire et la div pour les résultats
        const recipeForm = document.getElementById('recipeForm');
        const recipeContainer = document.getElementById('recipe');

        // Lors de la soumission du formulaire
        recipeForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page

            const query = document.getElementById('query').value.trim();

            if (query !== '') {
                // Appel à l'API du backend PHP pour récupérer les recettes
                fetch('/SwipeTonSouper_MySQL/web/api_tempo/getRecipe.php?query=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        let recipeHTML = '';
                        let cycle = 0; // Compteur pour le cycle de recherche


                        // Vérifie si des recettes sont trouvées
                        if (data.results && data.results.length > 0) {
                            // On récupère les détails pour chaque recette
                            data.results.forEach(recipe => {
                                // Appel à l'API pour récupérer plus de détails pour chaque recette
                                fetch(`/SwipeTonSouper_MySQL/web/api_tempo/getRecipeInfo.php?recipeId=${recipe.id}`)
                                    .then(response => response.json())
                                    .then(recipeDetails => {
                                        //tabJsonRecipes[cycle] = recipeDetails; // Stocke la recette dans le tableau
                                        tabJsonRecipes.push({
                                            no: cycle + 1, // Ajoute le numéro de la recette (cycle + 1) pour suivre l'ordre
                                            recipe: recipeDetails // Ajoute les détails de la recette
                                        }); // Ajoute l'objet avec le numéro et les détails de la recette au tableau


                                        // Construction de l'HTML pour la recette détaillée
                                        let ingredientsList = '';
                                        if (recipeDetails.extendedIngredients && recipeDetails.extendedIngredients.length > 0) {
                                            ingredientsList = `<h3>Ingrédients :</h3><ul>`;
                                            recipeDetails.extendedIngredients.forEach(ingredient => {
                                                ingredientsList += `
                                                    <li>
                                                        <img src="https://spoonacular.com/cdn/ingredients_100x100/${ingredient.image}" alt="${ingredient.name}" width="50">
                                                        ${ingredient.original}
                                                    </li>
                                                `;
                                            });
                                            ingredientsList += `</ul>`;
                                        }

                                        let instructions = '';
                                        if (recipeDetails.analyzedInstructions && recipeDetails.analyzedInstructions.length > 0) {
                                            instructions = `<h3>Instructions :</h3><ol>`;
                                            recipeDetails.analyzedInstructions[0].steps.forEach(step => {
                                                instructions += `<li class="step-input">${step.step}</li>`;
                                            });
                                            instructions += `</ol>`;
                                        }

                                        let priceInDollars = recipeDetails.pricePerServing / 100; // Dividing by 100 to convert cents to dollars
                                        let formattedPrice = priceInDollars.toFixed(2); // Format it to 2 decimal places
                                        let additionalDetails = `
                                            <p class="prepTime"><strong>Prêt en :</strong> ${recipeDetails.readyInMinutes} minutes</p>
                                            <p><strong>Portions :</strong> ${recipeDetails.servings}</p>
                                            <p><strong>Score santé :</strong> ${recipeDetails.healthScore}</p>
                                            <p><strong>Prix par portion :</strong> $${formattedPrice}</p>
                                            <p><strong>Types de régimes :</strong> ${recipeDetails.diets.join(', ')}</p>
                                            <p><strong>Source :</strong> <a href="${recipeDetails.sourceUrl}" target="_blank">${recipeDetails.sourceName}</a></p>
                                            <p class="description" ><strong>Résumé :</strong> ${recipeDetails.summary}</p>
                                        `;

                                        recipeHTML += `
                                        
                                            <form class="form-container2">
                                                <div>
                                                    <p class="recipe-genere">no : ${cycle + 1}</p>
                                                    <h2>${recipe.title}</h2>
                                                    <img src="${recipe.image}" alt="${recipe.title}" width="300">
                                                    <p><a href="https://spoonacular.com/recipes/${recipe.title.replace(/\s/g, '-')}-${recipe.id}" target="_blank">Voir la recette complète</a></p>
                                                    ${additionalDetails}
                                                    ${ingredientsList}
                                                    ${instructions}
                                                    <hr>
                                                </div>


                                                <div class="form-actions">
                                                    <button type="submit" class="save-recipe">Enregistrer</button>
                                                </div>
                                            </form>
                                        
                                        `;
                                        // Mettre à jour le contenu HTML des recettes
                                        recipeContainer.innerHTML = recipeHTML;
                                        cycle++; // Incrémente le compteur de cycle
                                    })
                                    .catch(error => console.error('Erreur lors de la récupération des détails de la recette:', error));
                            });
                        } else {
                            recipeContainer.innerHTML = 'Aucune recette trouvée.';
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des recettes:', error);
                        recipeContainer.innerHTML = 'Une erreur est survenue.';
                    });
            } else {
                recipeContainer.innerHTML = 'Veuillez entrer une requête de recherche.';
            }
        });




        $(document).ready(function() {
            $(document).on('submit', '.form-container2', function(e) {
                e.preventDefault();
                const $form = $(this);
                const noRecipe = $form.find('.recipe-genere').text(); // Récupère le numéro de la recette
                const numeroTab = parseInt(noRecipe.split(':')[1]); // Extrait le numéro de la recette
                const recipeTitle = $form.find('h2').text(); // Récupère le titre de la recette
                const description = $form.find('.description').text(); // Récupère la description de la recette

                const textTime = $form.find('.prepTime').text(); // Récupère le temps de préparation
                const prepTime = parseInt(textTime.split(':')[1]); // Extrait le temps de préparation
                const recipeImage = $form.find('img').attr('src'); // Récupère l'URL de l'image de la recette


                const recipeData = tabJsonRecipes[numeroTab - 1].recipe; // Récupère les données de la recette à partir du tableau  
                // let firstRecipe = tabJsonRecipes[0].recipe; // La première recette
                // let recipeNumber = tabJsonRecipes[0].no; // Le numéro de la première recette


                // Extraction des ingrédients (extendedIngredients)
                const extendedIngredients = recipeData.extendedIngredients;

                // Initialisation du tableau des ingrédients
                let ingredientTab = [];
                let cycle = 0;

                // Parcours des ingrédients pour remplir `ingredientTab`
                extendedIngredients.forEach(function(ingredient, index) {
                    let unite = ingredient.measures?.us?.amount || ingredient.measures?.metric?.amount || 0; // Récupère l'unité de mesure ou une chaîne vide
                    
                    let ingredientName = ingredient.name; // Nom de l'ingrédient
                    let unitOfMeasure = ingredient.measures ? ingredient.measures.us.unitShort || ingredient.unit : unite; // Récupère l'unité de mesure en fonction des unités US ou autres

                    // Ajoute l'élément à `ingredientTab` si l'unité est renseignée
                    if (unite) {
                        ingredientTab.push({
                            numero: index + 1, // Le numéro de l'ingrédient
                            unite: unite, // L'unité de l'ingrédient
                            ingredient: ingredientName, // Nom de l'ingrédient
                            unitOfMeasure: unitOfMeasure // Unité de mesure détaillée (par ex. "cups", "grams", etc.)
                        });
                    }
                    cycle++;
                });

                // Log de tous les ingrédients extraits
                console.log("Ingrédients extraits : ", ingredientTab);



                // Parcours des étapes pour remplir `steps`
                let steps = []; // Initialisation du tableau des étapes
                let cycle2 = 0; // Compteur pour le cycle

                // Vérification que `analyzedInstructions` existe et contient des étapes
                if (recipeData.analyzedInstructions && recipeData.analyzedInstructions.length > 0) {
                    // On suppose qu'il y a seulement un ensemble d'instructions
                    const instructions = recipeData.analyzedInstructions[0].steps;

                    // Parcours des étapes
                    instructions.forEach(function(step, index) {
                        // On récupère la description de chaque étape
                        let description = step.step || ''; // Description de l'étape

                        // Ajoute l'étape à `steps` sous la forme d'un objet avec des clés spécifiques
                        steps.push({
                            numero: index + 1, // Le numéro de l'étape (index + 1 pour commencer à 1)
                            description: description // La description de l'étape
                        });

                        cycle2++; // Incrémentation du compteur de cycle
                    });
                }

                // Log de toutes les étapes extraites
                console.log("Étapes extraites : ", steps);


                var formData = new FormData();
                formData.append('name', recipeTitle);
                formData.append('description', description);
                formData.append('difficulty', 0); // Difficulté par défaut : non disponible
                formData.append('prepTime', prepTime); // Temps de préparation
                formData.append('ingredients', JSON.stringify(ingredientTab)); // Ingrédients au format JSON

                formData.append('steps', JSON.stringify(steps));
                formData.append('image-web', recipeImage); // Ajout de l'image,  À vérifier si c'Est JPEG seulement






                // Envoi des données via AJAX avec FormData
                $.ajax({
                    url: '/SwipeTonSouper_MySQL/web/api_tempo/recipe/enregistrer_recette.php',
                    method: 'POST',
                    data: formData,
                    processData: false, // Ne pas traiter les données (pour les fichiers)
                    contentType: false, // Laisser FormData gérer le contentType
                    success: function(response) {
                        console.log("Réponse brute de l'API :", response);


                        if (response.message) {
                            alert(response.message);
                            //localStorage.removeItem('manualRecipe'); // Nettoyage du localStorage
                            //window.location.href = '?action=voirMesRecettes'; // Rediriger vers une page de succès
                        }

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('Erreur:', textStatus, errorThrown);
                        console.log('Réponse de l\'API:', jqXHR.responseText); // Affiche la réponse brute de l'API
                        // Afficher une alerte d'erreur
                        alert('Erreur lors de l\'enregistrement.');
                    }
                });

                // Affichage des données dans la console (pour le débogage)
                console.log('Données de la recette:', recipeData);
                console.log('Étapes:', steps);
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }


            });
        });
    </script>

</body>

</html>