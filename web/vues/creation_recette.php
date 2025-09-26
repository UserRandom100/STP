<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Swipe Ton Souper - Création de Recette</title>
  <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/connecter.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/creer_recette.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/board.css">
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
      //Verifiaction de l'URL pour un id de recette
      $idRecette = filter_input(INPUT_GET,  'idRecette', FILTER_VALIDATE_INT);
      // Si l'idRecette est valide, on charge la recette
      $donneesRecette = null;
      if($idRecette != NULL && $idRecette != false) {
        // Est-ce qu'on a une fonction pour charger la recette par idRecette ?
        //$donneesRecette = getRecipeById($idRecette);
      }
      ?>
    </div>
  </header>

  <main>
    <div class="container">
      <div class="form-container">
        <section class="board">
          <h3 class="SousTitre">Partage ta meilleure recette avec la communauté !</h3>
          <form id="recipe-form">

            <div class="form-layout">
              <!-- Colonne gauche -->
              <section class="left-column">
                <div class="form-group">
                  <label for="recipe-name">Nom de la recette</label>
                  <input class="form-control" type="text" id="recipe-name" name="recipe-name" placeholder="Nom de la recette" required>
                </div>
                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" placeholder="Description de la recette" required></textarea>
                </div>

                <div class="form-group">
                  <label for="difficulty">Difficulté</label>

                  <input name="difficulty" id="difficulty" type="range" min="1" max="3" step="1" value="1">
                  <output class="difficulty-output">Facile</output>
                  <script>
                    document.getElementById('difficulty').addEventListener('input', function() {
                      const labels = ["Facile", "Moyenne", "Difficile"];
                      this.nextElementSibling.textContent = labels[this.value - 1];
                    });
                  </script>

                </div>


                <div class="form-group">
                  <label for="prep-time">Temps de préparation</label>
                  <input class="form-control" type="number" id="prep-time" name="prep-time" min="0" placeholder="Temps de préparation (minutes)" required>
                </div>
                <!-- <div class="form-group">
                  <label for="recipe-image">Photo de la recette</label>
                  <input class="form-control" type="file" id="recipe-image" name="recipe-image" accept="image/*">
                </div> -->
              </section>

              <!-- Colonne droite -->
              <!-- Section des ingrédients -->
              <section class="right-column">
                <div class="form-group">
                  <label for="ingredients">Ingrédients</label>
                  <input class="form-control" type="text" id="ingredient-search" name="ingredient-search" placeholder="Chercher un ingrédient" required>
                  <ul id="ingredient-suggestions"></ul>
                  <!-- <button type="button" class="btn" id="add-ingredient">Ajouter</button> -->
                  <ul id="ingredients-list"></ul>
                </div>
              </section>
            </div>

            <!-- Boutons -->
            <div class="form-actions">
              <!-- <button class="btn" type="button" id="generate-ai">Générer avec IA</button> -->
              <button class="btn" type="button" id="next-manual">Suivant</button>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>
  <script>
    $(document).ready(function() {
      var ingredientsList = []; // Liste des ingrédients ajoutés
      var unitesList = []; // Liste des unités pour chacun des ingrédients ajoutés
      // Fonction pour rechercher les ingrédients dans la base de données
      $('#ingredient-search').on('input', function() {
        var searchQuery = $(this).val();

        // Rechercher seulement si la longueur du texte est > 2
        if (searchQuery.length > 2) {
          $.ajax({
            url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/ingredient/rechercher_ingredients.php', // Ton URL API
            method: 'GET',
            data: {
              query: searchQuery
            }, // Paramètre de recherche
            dataType: 'json', // Réponse attendue en JSON
            success: function(response) {
              var suggestionsHtml = '';


              // Si Aucun ingrédient trouvé
              if (response.length == 0) {
                suggestionsHtml = '<li class="no-result">Aucun ingrédient trouvé</li>';
              }

              // Si des ingrédients sont trouvés
              if (response.length > 0) {
                response.forEach(function(ingredient) {
                  suggestionsHtml += '<li class="suggestion-item" data-id="' + ingredient.id + '" data-name="' + ingredient.name + '" data-ingredient="' + ingredient.unitOfMeasure + '">' + ingredient.name + ' (' + ingredient.unitOfMeasure + ')</li>';
                });
              } else {
                // Si aucun ingrédient trouvé
                suggestionsHtml = '<li class="no-result">Aucun ingrédient trouvé</li>';
              }

              // Affichage des suggestions sous le champ de recherche
              $('#ingredient-suggestions').html(suggestionsHtml);
            },
            error: function() {
              alert('Erreur lors de la recherche des ingrédients.');
            }
          });
        } else {
          // Effacer les suggestions si la recherche est trop courte
          $('#ingredient-suggestions').html('');
        }
      });


      // Ajouter un ingrédient à la liste lorsqu'on clique sur une suggestion
      $('#ingredient-suggestions').on('click', '.suggestion-item', function() {
        var ingredientName = $(this).data('name');
        var unite = $(this).data('ingredient');

        // Vérifier si l'ingrédient est déjà dans la liste pour éviter les doublons
        if (!ingredientsList.includes(ingredientName)) {
          ingredientsList.push(ingredientName);
          unitesList.push(unite);

          $('#ingredients-list').append('<li>' + ingredientName + ' ' +
            '<input class="form-control quantity-ingredient" type="number" name="quantity" min ="0" placeholder="nb" required>' +
            ' ' + unite + '</li>'); // Ajout à la liste affichée
        }

        // Réinitialiser le champ de recherche et les suggestions après sélection
        $('#ingredient-search').val('');
        $('#ingredient-suggestions').html('');
      });




      // Option 1: Générer la recette avec l'IA
      $('#generate-ai').click(function() {
        let ingredientTab = [];
        let cycle = 0;

        // Parcours des ingrédients et quantités
        $('.quantity-ingredient').each(function(index) {
          let unite = $(this).val().trim();
          if (unite) {
            ingredientTab.push({
              numero: index + 1,
              unite: unite,
              ingredient: ingredientsList[cycle],
              unitOfMeasure: unitesList[cycle]
            });
          }
          cycle++;
        });

        // Création d'un objet FormData
        var formData = new FormData();

        // Ajout des données classiques
        formData.append('name', $('#recipe-name').val() || '');
        formData.append('description', $('#description').val() || '');
        formData.append('difficulty', $('#difficulty').val() || 1);
        formData.append('prepTime', $('#prep-time').val() || 1);
        formData.append('ingredients', JSON.stringify(ingredientTab) || []); // Envoi sous forme de chaîne JSON

        // Ajout de l'image (si un fichier a été sélectionné)
        // var image = $('#recipe-image')[0].files[0];
        // if (image) {
        //   formData.append('image', image);
        // }

        // Envoi des données via AJAX avec FormData
        $.ajax({
          url: 'generate_recipe.php', // Script qui gère la génération de la recette par IA
          method: 'POST',
          data: formData,
          processData: false, // Ne pas traiter les données (pour les fichiers)
          contentType: false, // Laisser FormData définir le contentType
          success: function(response) {
            // Redirige vers la page de la recette générée avec les données en paramètre d'URL
            window.location.href = '?action=creerRecetteAI&recipe=' + encodeURIComponent(JSON.stringify(response));
          },
          error: function() {
            alert('Erreur lors de la génération de la recette.');
          }
        });
      });

      // Option 2: Passer à la création manuelle de la recette
      $('#next-manual').click(function() {

        let ingredientTab = [];
        let cycle = 0;
        $('.quantity-ingredient').each(function(index) {
          let unite = $(this).val().trim();
          if (unite) {
            ingredientTab.push({
              numero: index + 1,
              unite: unite,
              ingredient: ingredientsList[cycle],
              unitOfMeasure: unitesList[cycle]
            });
          }
          cycle++;
        });


        var recipeData = {
          name: $('#recipe-name').val() || '', // Si vide, valeur par défaut ''
          description: $('#description').val() || '', // Si vide, valeur par défaut ''
          difficulty: $('#difficulty').val() || '', // Si vide, valeur par défaut ''
          prepTime: $('#prep-time').val() || '', // Si vide, valeur par défaut ''
          ingredients: ingredientTab || [], // Si la liste est vide, valeur par défaut []
         // image: $('#recipe-image')[0].files[0] || null // Si pas de fichier, valeur par défaut null
        };

        // Stockage des données en local pour la page suivante (ajout des étapes)
        localStorage.setItem('manualRecipe', JSON.stringify(recipeData));

        // Redirection vers la page d'ajout des étapes
        window.location.href = '?action=creerRecetteSuite';
      });

    });
  </script>
</body>

</html>