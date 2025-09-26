<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Swipe Ton Souper - Modification de Recette</title>
  <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/connecter.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/creer_recette.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/board.css">
  <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
  <script type="text/javascript" src="./assets/js/jquery-3.6.1.min.js"></script>
  <script type="text/javascript" src="./assets/js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="./assets/js/additional-methods.min.js"></script>
  <script type="text/javascript" src="./assets/js/messages_fr.min.js"></script>
  <script src="assets/js/modification.js"></script>

</head>

<body>
  <header>
    <div class="header-container">
      <?php include("vues/inclusions/logo.inc.php");
      include_once "vues/inclusions/nav_bar.inc.php";
      afficherMenu($controleur);

      // Recipe data verification
      $idRecette = filter_input(INPUT_GET, 'idRecette', FILTER_VALIDATE_INT);
      $donneesRecette = null;
      $ingredientsArray = [];
      $stepsArray = [];

      if ($idRecette !== null && $idRecette !== false) {
        require_once 'modele/DAO/RecipeDAO.class.php';
        require_once 'modele/DAO/RecipeIngredientDAO.class.php';
        require_once 'modele/DAO/IngredientDAO.class.php';
        require_once 'modele/DAO/StepDAO.class.php';

        // Verify recipe exists and belongs to current user
        $donneesRecette = RecipeDAO::findById($idRecette);
        $currentUserId = $_SESSION['userId'] ?? null;

        if (!$donneesRecette || $donneesRecette->getIdCreator() != $currentUserId) {
          echo "<script>alert('Recette introuvable ou accès non autorisé.'); window.location.href='?action=voirMesRecettes';</script>";
          exit;
        }

        // Get existing ingredients
        $ingredientLinks = RecipeIngredientDAO::findByIdRecipe($idRecette);
        foreach ($ingredientLinks as $link) {
          $ingredient = IngredientDAO::findById($link->getIngredientId());
          if ($ingredient) {
            $ingredientsArray[] = [
              'id' => $ingredient->getId(),
              'name' => $ingredient->getName(),
              'quantity' => $link->getQuantity(),
              'unitOfMeasure' => $link->getUnitOfMeasure()
            ];
          }
        }


        $steps = StepDAO::findByIdRecipe($idRecette);
        foreach ($steps as $step) {
          $stepsArray[] = [
            'stepNumber' => $step->getStepNumber(),
            'description' => $step->getStepDescription()

          ];
        }
      }
      ?>

      <script>
        const existingIngredients = <?php echo json_encode($ingredientsArray); ?>;
        const existingSteps = <?php echo json_encode($stepsArray); ?>;
        const recipeId = <?php echo $idRecette ?? 'null'; ?>;
      </script>
    </div>
  </header>
  <main>
    <div class="container">
      <div class="form-container">
        <section class="board">
          <h3 class="SousTitre">Modifier votre recette</h3>
          <form id="recipe-form" method="post" action="?action=sauvegarderModificationRecette"
            enctype="multipart/form-data">
            <input type="hidden" name="idRecette" id="idRecette" value="<?php echo $idRecette; ?>">


            <div class="form-layout">
              <!-- Left column -->
              <section class="left-column">
                <div class="form-group">
                  <label for="recipe-name">Nom de la recette</label>
                  <input class="form-control" type="text" id="recipe-name" name="recipe-name" required
                    value="<?php echo htmlspecialchars($donneesRecette->getTitle()) ?>">
                </div>

                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="recipe-description" name="description">

                    <?php
                    echo htmlspecialchars($donneesRecette->getDescription());
                    ?></textarea>
                </div>

                <div class="form-group">
                  <label for="difficulty">Difficulté</label>
                  <input name="difficulty" id="difficulty" type="range" min="1" max="3" step="1"
                    value="<?php echo htmlspecialchars($donneesRecette->getDifficulty()); ?>">
                  <output class="difficulty-output">
                    <?php
                    $labels = ["Facile", "Moyenne", "Difficile"];
                    $diffIndex = max(1, (int) $donneesRecette->getDifficulty()) - 1;
                    echo $labels[$diffIndex];
                    ?>
                  </output>
                </div>



                <div class="form-group">
                  <label for="prep-time">Temps de préparation</label>
                  <input class="form-control" type="number" id="prep-time" name="prep-time" required
                    value="<?php echo htmlspecialchars($donneesRecette->getPrepTime()); ?>">
                </div>

                <div class="form-group">
                  <label for="recipe-image">Photo de la recette</label>
                  <input class="form-control" type="file" id="recipe-image" name="recipe-image" accept="image/*">
                  <?php if ($donneesRecette->getImagePath()): ?>
                    <p>Image actuelle: <?php echo basename($donneesRecette->getImagePath()); ?></p>
                  <?php endif; ?>
                </div>

              </section>

              <!-- Right column -->
              <section class="right-column">
                <div class="form-group">
                  <label for="ingredients">Ingrédients</label>
                  <input class="form-control" type="text" id="ingredient-search" placeholder="Chercher un ingrédient">
                  <ul id="ingredient-suggestions"></ul>
                  <ul id="ingredients-list"></ul>
                </div>

                <div class="form-group">
                  <label>Étapes de préparation</label>
                  <div id="steps-container">
                    <!-- Steps will be added here dynamically -->
                  </div>
                  <button type="button" id="add-step" class="btn">Ajouter une étape</button>
                </div>
              </section>
            </div>

            <!-- Buttons -->
            <div class="form-actions">
              <button class="btn" type="submit" id="save-recipe">Sauvegarder</button>
              <a href="?action=informationRecette&recipeId=<?php echo $idRecette; ?>" class="btn">Annuler</a>
            </div>
          </form>
        </section>
      </div>
    </div>
  </main>

  <script>
    $(document).ready(function() {
      // Initialize variables
      var ingredientsList = [];
      var unitesList = [];
      var ingredientIds = [];
      var steps = [];

      // Difficulty slider update
      $('#difficulty').on('input', function() {
        const labels = ["Facile", "Moyenne", "Difficile"];
        $('.difficulty-output').text(labels[$(this).val() - 1]);
      });



      // Load existing ingredients
      if (typeof existingIngredients !== 'undefined' && existingIngredients.length > 0) {
        existingIngredients.forEach(function(ingredientObj) {
          const name = ingredientObj.name;
          const quantity = ingredientObj.quantity;
          const unit = ingredientObj.unitOfMeasure;
          const id = ingredientObj.id;

          ingredientsList.push(name);
          unitesList.push(unit);
          ingredientIds.push(id);

          $('#ingredients-list').append(`
            <li data-id="${id}">
              ${name} 
              <input class="form-control quantity-ingredient" type="number" 
                     name="ingredients[${id}][quantity]" value="${quantity}" min="0" required>
              <input type="hidden" name="ingredients[${id}][id]" value="${id}">
              <input type="hidden" name="ingredients[${id}][unit]" value="${unit}">
              ${unit}
              <button type="button" class="remove-ingredient btn-small">×</button>
            </li>
          `);
        });
      }

      // Load existing steps
      if (typeof existingSteps !== 'undefined' && existingSteps.length > 0) {
        existingSteps.forEach(function(step, index) {
          addStep(step.stepNumber, step.description);
        });
      } else {
        // Add one empty step if none exist
        addStep(1, '');
      }

      // Add step button
      $('#add-step').click(function() {
        const nextStepNumber = steps.length > 0 ? Math.max(...steps.map(s => s.number)) + 1 : 1;
        addStep(nextStepNumber, '');
      });

      // Function to add a step
      function addStep(number, description) {
        const stepId = 'step-' + Date.now();

        $('#steps-container').append(`
          <div class="step" id="${stepId}" data-number="${number}">
            <h4>Étape ${number}</h4>
            <textarea class="form-control" name="steps[${number}]" required>${description}</textarea>
            <button type="button" class="remove-step btn-small">Supprimer</button>
          </div>
        `);

        steps.push({
          id: stepId,
          number: number
        });
      }

      // Remove step handler
      $(document).on('click', '.remove-step', function() {
        const stepDiv = $(this).closest('.step');
        stepDiv.remove();

        // Reorder remaining steps
        $('.step').each(function(index) {
          const newNumber = index + 1;
          $(this).attr('data-number', newNumber);
          $(this).find('h4').text('Étape ' + newNumber);
          $(this).find('textarea').attr('name', 'steps[' + newNumber + ']');
        });
      });

      // Ingredient search functionality
      $('#ingredient-search').on('input', function() {
        var searchQuery = $(this).val();
        if (searchQuery.length > 2) {
          $.ajax({
            url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/ingredient/rechercher_ingredients.php',
            method: 'GET',
            data: {
              query: searchQuery
            },
            dataType: 'json',
            success: function(response) {
              var suggestionsHtml = '';
              if (response.length > 0) {
                response.forEach(function(ingredient) {
                  suggestionsHtml += `
                    <li class="suggestion-item" 
                        data-id="${ingredient.id}" 
                        data-name="${ingredient.name}" 
                        data-unit="${ingredient.unitOfMeasure}">
                      ${ingredient.name} (${ingredient.unitOfMeasure})
                    </li>`;
                });
              } else {
                suggestionsHtml = '<li class="no-result">Aucun ingrédient trouvé</li>';
              }
              $('#ingredient-suggestions').html(suggestionsHtml);
            },
            error: function() {
              alert('Erreur lors de la recherche des ingrédients.');
            }
          });
        } else {
          $('#ingredient-suggestions').html('');
        }
      });

      // Add ingredient from suggestion
      $('#ingredient-suggestions').on('click', '.suggestion-item', function() {
        var ingredientId = $(this).data('id');
        var ingredientName = $(this).data('name');
        var unit = $(this).data('unit');

        if (!ingredientIds.includes(ingredientId)) {
          ingredientIds.push(ingredientId);
          ingredientsList.push(ingredientName);
          unitesList.push(unit);

          $('#ingredients-list').append(`
            <li data-id="${ingredientId}">
              ${ingredientName} 
              <input class="form-control quantity-ingredient" type="number" 
                     name="ingredients[${ingredientId}][quantity]" min="0" placeholder="nb" required>
              <input type="hidden" name="ingredients[${ingredientId}][id]" value="${ingredientId}">
              <input type="hidden" name="ingredients[${ingredientId}][unit]" value="${unit}">
              ${unit}
              <button type="button" class="remove-ingredient btn-small">×</button>
            </li>
          `);
        }

        $('#ingredient-search').val('');
        $('#ingredient-suggestions').html('');
      });

      // Remove ingredient
      $(document).on('click', '.remove-ingredient', function() {
        const ingredientLi = $(this).closest('li');
        const ingredientId = ingredientLi.data('id');

        // Remove from arrays
        const index = ingredientIds.indexOf(ingredientId);
        if (index > -1) {
          ingredientIds.splice(index, 1);
          ingredientsList.splice(index, 1);
          unitesList.splice(index, 1);
        }

        ingredientLi.remove();
      });





      $('#recipe-form').on('submit', function(e) {
        e.preventDefault(); // Empêche la soumission normale

        const form = this;
        const formData = new FormData(form);

        // Vérification de l’image
        const imageFile = $('#recipe-image')[0].files[0];
        if (imageFile) {
          const allowedTypes = ['image/jpeg'];
          if (allowedTypes.includes(imageFile.type)) {
            formData.append('recipe-image', imageFile); // Assure le nom correct
          } else {
            alert('Veuillez télécharger une image en format JPEG ou PNG.');
            return;
          }
        }

        // Vérifie qu’il y a au moins une étape
        if ($('.step').length === 0) {
          alert('Veuillez ajouter au moins une étape.');
          return;
        }

        // Envoi via AJAX
        $.ajax({
          url: $(form).attr('action'),
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            // Rediriger ou afficher un message
            window.location.href = "?action=voirMesRecettes"; // Redirige vers la page de recettes
          },
          error: function() {
            alert("Une erreur est survenue lors de l'envoi du formulaire.");
          }
        });
      });









      // Form submission
      $('#recipe-form').validate({
        submitHandler: function(form) {
          // Additional validation if needed
          if ($('.step').length === 0) {
            alert('Veuillez ajouter au moins une étape.');
            return false;
          }

          form.submit();
        }
      });
    });
  </script>
</body>

</html>