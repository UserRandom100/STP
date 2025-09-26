<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Récupération des données textuelles
  $name = $_POST['name'];
  $description = $_POST['description'];
  $difficulty = $_POST['difficulty'];
  $prepTime = $_POST['prepTime'];
  $ingredients = json_decode($_POST['ingredients'], true);

  // Récupération de l'image
  if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($image['name']);
    move_uploaded_file($image['tmp_name'], $uploadFile);
  }

  // Exemple de réponse JSON
  echo json_encode([
    'status' => 'success',
    'message' => 'Recette générée avec succès',
    'data' => [
      'name' => $name,
      'description' => $description,
      'difficulty' => $difficulty,
      'prepTime' => $prepTime,
      'ingredients' => $ingredients
    ]
  ]);
}
?>
