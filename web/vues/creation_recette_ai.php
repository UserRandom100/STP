<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Swipe Ton Souper - Recette Générée</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/connecter.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/creer_recette.css">
  <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
  <script type="text/javascript" src="../assets/js/jquery-3.6.1.min.js"></script>
  <script type="text/javascript" src="../assets/js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="../assets/js/additional-methods.min.js"></script>
  <script type="text/javascript" src="../assets/js/messages_fr.min.js"></script>
</head>
<body>
  <header>
    <div class="header-container">
      <div class="Logo">Swipe Ton Souper</div>
      <nav class="navbar">
        <button class="nav-button active">Créer</button>
        <button class="nav-button">Mes Recettes</button>
        <button class="nav-button">Compte</button>
      </nav>
    </div>
  </header>
  <main>
    <div class="container">
      <div class="recette-generee-container">
        <h3 class="sous-titre">Recette Générée</h3>
        <div class="recette-details">
          <h4 id="recipe-name"></h4>
          <p id="recipe-description"></p>
          <p><strong>Difficulté:</strong> <span id="recipe-difficulty"></span></p>
          <p><strong>Temps de préparation:</strong> <span id="recipe-prep-time"></span> minutes</p>
          <ul id="ingredients-list"></ul>
          <p><strong>Instructions:</strong></p>
          <p id="recipe-steps"></p>
          <img src="" alt="Image de la recette" id="recipe-image">
        </div>
        <button class="btn" id="save-recipe">Sauvegarder la recette</button>
      </div>
    </div>
  </main>
  <script>
    var recipeData = JSON.parse(localStorage.getItem('finalRecipe'));
    if (recipeData) {
      $('#recipe-name').text(recipeData.name);
      $('#recipe-description').text(recipeData.description);
      $('#recipe-difficulty').text(recipeData.difficulty);
      $('#recipe-prep-time').text(recipeData.prepTime);
      recipeData.ingredients.forEach(function(ingredient) {
        $('#ingredients-list').append('<li>' + ingredient + '</li>');
      });
      $('#recipe-steps').text(recipeData.steps);  // Affiche les étapes
      $('#recipe-image').attr('src', URL.createObjectURL(recipeData.image));
    }

    $('#save-recipe').click(function() {
      alert('Recette sauvegardée dans la base de données!');
    });
  </script>
</body>
</html>
