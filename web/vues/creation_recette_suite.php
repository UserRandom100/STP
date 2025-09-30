<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Swipe Ton Souper - Étapes de Recette</title>

  <link rel="stylesheet" type="text/css" href="./assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/connecter.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/board.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/creer_recette.css">
  <link rel="stylesheet" type="text/css" href="./assets/css/creer_recette_suit.css">

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
      <form id="steps-form" class="form-container">
        <section class="board">

        <h3 class="SousTitre">Photo</h3>
          <div class="form-group">
            <label for="recipe-image">Photo de la recette</label>
            <input class="form-control" type="file" id="recipe-image" name="recipe-image" accept="image/*">
          </div>

          <h3 class="SousTitre">Étapes de préparation</h3>
          <div id="steps-container">
            <!-- Étapes ajoutées dynamiquement ici -->
          </div>
          <div class="form-actions">
            <button type="button" id="add-step">Ajouter une étape</button>
            <button type="submit" id="save-recipe">Enregistrer</button>
          </div>
        </section>
      </form>
    </div>
  </main>

  <script>
    $(document).ready(function() {
      let stepCount = 1;

      function updateStepNumbers() {
        $('.step').each(function(index) {
          $(this).attr('id', `step_${index + 1}`);
          $(this).find('label').text(`Étape ${index + 1}`);
          $(this).find('textarea').attr('name', `step_${index + 1}`);
          $(this).find('.remove-step').attr('data-step', `step_${index + 1}`);
        });
        stepCount = $('.step').length + 1;
      }

      function addStep() {
        const stepId = `step_${stepCount}`;
        const stepHtml = `
          <div class="form-group step" id="${stepId}">
            <label>Étape ${stepCount}</label>
            <button type="button" class="remove-step" data-step="${stepId}">🗑️ Supprimer</button>
            <br>
            <textarea class="form-control step-input" name="${stepId}" placeholder="Description de l'étape..." required></textarea>
          </div>`;
        $('#steps-container').append(stepHtml);
        stepCount++;
      }

      $('#add-step').click(addStep);
      addStep(); // Ajoute la première étape par défaut

      $(document).on('click', '.remove-step', function() {
        $(this).parent().remove();
        updateStepNumbers();
      });

      $('#steps-form').submit(function(e) {
        e.preventDefault();

        let steps = [];

        // $('.step-input').each(function(index) {
        //   steps.push({
        //     numero: index + 1,
        //     description: $(this).val()
        //   });
        // });

        // Pour ne pas envoyer des étapes vides, 
        // filtrer les entrées vides avant de les 
        // ajouter au tableau steps
        $('.step-input').each(function(index) {
          let description = $(this).val().trim();
          if (description) {
            steps.push({
              numero: index + 1,
              description: description
            });
          }
        });


        // Récupération de la recette en local (sans ID)
        let recipeData = JSON.parse(localStorage.getItem('manualRecipe')) || {};
        recipeData.etapes = steps; // Ajout des étapes


        // // Vérification des données essentielles (facultatif mais recommandé)
        // if (!recipeData.name || !recipeData.description) {
        //   alert('Le nom de la recette et la description sont obligatoires.');
        //   return; // Empêche l'envoi si les données sont incomplètes
        // }


        // Envoi à la base de données
        // $.ajax({
        //   url: 'http://localhost/SwipeTonSouper_MySQL/web/api_tempo/recipe/enregistrer_recette.php',
        //   method: 'POST',
        //   contentType: 'application/json',
        //   data: JSON.stringify(recipeData),
        //   success: function(response) {
        //     if (response.message) {
        //       alert(response.message);
        //       localStorage.removeItem('manualRecipe'); // Nettoyage du localStorage
        //       window.location.href = '?action=voirMesRecettes'; // Redirige vers une page de confirmation ou de succès
        //     }
        //     console.log('Réponse de l\'API:', response);
        //   },
        //   error: function(jqXHR, textStatus, errorThrown) {
        //     console.log('Erreur:', textStatus, errorThrown);
        //     console.log('Réponse de l\'API:', jqXHR.responseText); // Affiche la réponse brute de l'API
        //     alert('Erreur lors de l\'enregistrement.');
        //   }

        // });


        // Création d'un objet FormData pour envoyer les données
        var formData = new FormData();

        // Ajouter les données classiques de la recette
        formData.append('name', recipeData.name);
        formData.append('description', recipeData.description);
        formData.append('difficulty', recipeData.difficulty);
        formData.append('prepTime', recipeData.prepTime);
        formData.append('ingredients', JSON.stringify(recipeData.ingredients));
        formData.append('steps', JSON.stringify(recipeData.etapes));

        // Si une image a été ajoutée
        // if (recipeData.image) {
        //   const allowedTypes = ['image/jpeg', 'image/png'];
        //   if (allowedTypes.includes(recipeData.image.type)) {
        //     formData.append('image', recipeData.image);
        //   } else {
        //     alert('Veuillez télécharger une image JPEG ou PNG.');
        //     return; // Stoppe l'envoi si l'image n'est pas valide
        //   }
        // }





        // Si une image a été ajoutée, l'ajouter au FormData
        if ($('#recipe-image')[0].files[0]) {
          const imageFile = $('#recipe-image')[0].files[0];
          const allowedTypes = ['image/jpeg'];
          if (allowedTypes.includes(imageFile.type)) {
            formData.append('image', imageFile);
          } else {
            alert('Veuillez télécharger une image en format JPEG.');
            return; // Stoppe l'envoi si l'image n'est pas valide
          }
        }








        // Envoi des données via AJAX avec FormData
        $.ajax({
          url: '../web/api_tempo/recipe/enregistrer_recette.php',
          method: 'POST',
          data: formData,
          processData: false, // Ne pas traiter les données (pour les fichiers)
          contentType: false, // Laisser FormData gérer le contentType
          success: function(response) {
            console.log("Réponse brute de l'API :", response);
            // try {
            //   let parsedResponse = JSON.parse(response); // Tente de parser la réponse comme JSON
            //   if (parsedResponse.message) {
            //     alert(parsedResponse.message);
            //     localStorage.removeItem('manualRecipe'); // Nettoyage du localStorage
            //     window.location.href = '?action=voirMesRecettes'; // Redirige vers une page de confirmation
            //   }
            // } catch (error) {
            //   console.log("Erreur lors du parsing du JSON :", error);
            //   alert("La réponse du serveur n'est pas au format JSON.");
            // }

            if (response.message) {
              alert(response.message);
              localStorage.removeItem('manualRecipe'); // Nettoyage du localStorage
              window.location.href = '?action=voirMesRecettes'; // Rediriger vers une page de succès
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