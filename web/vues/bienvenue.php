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
  <link rel="stylesheet" type="text/css" href="./assets/css/bienvenue.css">
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
        <section class="board">
          <?php
          if (isset($_SESSION['utilisateurConnecte']) && $_SESSION['utilisateurConnecte'] instanceof User) {
            $utilisateurConnecte = $_SESSION['utilisateurConnecte'];
            $nom = htmlspecialchars($utilisateurConnecte->getName());
            $nomComplet = $nom;
          }
          ?>

          <h1 class="SousTitre">Salut, chef <?php echo $nomComplet; ?> ! *:･ﾟ✧</h1>

          <h2>🍰 Bienvenue sur Swipe Ton Souper ! 🍰</h2>
          <p>Ici, on ne se contente pas de suivre les recettes… on les invente avec amour 💕🍭</p>


          <p>Chez <span class="bienvenue-logo">Swipe Ton Souper</span>, on adore la cuisine sous toutes ses formes. </p>
          <p>Ici, tu peux à la fois explorer un univers infini de recettes et laisser parler
            ta créativité en inventant les tiennes. Que tu sois à la recherche de recettes
            traditionnelles ou de plats innovants, notre vaste base de données, alimentée par
            des recettes venues d’ailleurs et de chez nous, te propose tout un monde de possibilités ! ✨</p>

          <p>Mais ce n’est pas tout : lorsque tu trouves une recette qui te plaît, tu peux
            la personnaliser à ta façon, y ajouter tes propres touches et ainsi créer une
            recette unique, à ton image !</p>

          <p>Découvre des idées inspirantes, mixe les saveurs, et ajoute ta magie personnelle. ✨</p>



        </section>
      </div>
    </div>
  </main>
</body>

</html>