<?php
require 'database.php'; // Connexion à la BDD

// Lire les données JSON envoyées depuis JavaScript
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "error" => "Données invalides"]);
    exit;
}

try {
    $pdo->beginTransaction(); // Démarrer une transaction

    // Vérifier si c'est une nouvelle recette ou une mise à jour des étapes
    if (isset($data['id_recette']) && !empty($data['id_recette'])) {
        $recetteId = $data['id_recette']; // Utiliser l'ID existant
    } else {
        // Insérer une nouvelle recette dans la base de données
        $stmt = $pdo->prepare("INSERT INTO Recette (Titre, Description, Difficulte, TempsDePreparation) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['name'], $data['description'], $data['difficulty'], $data['prepTime']]);
        $recetteId = $pdo->lastInsertId(); // Récupérer l'ID de la recette ajoutée

        // Associer la recette à l'utilisateur (à remplacer par $_SESSION['user_id'] si un système de connexion est en place)
        $userId = 1; 
        $stmt = $pdo->prepare("INSERT INTO UserCreatedRecipe (UserId, RecetteId) VALUES (?, ?)");
        $stmt->execute([$userId, $recetteId]);

        // Insérer les ingrédients dans RecetteIngredient (si présents)
        if (!empty($data['ingredients'])) {
            foreach ($data['ingredients'] as $ingredient) {
                $stmt = $pdo->prepare("INSERT INTO RecetteIngredient (RecetteId, IngredientId, Quantite) VALUES (?, ?, ?)");
                $stmt->execute([$recetteId, $ingredient['id'], $ingredient['quantite']]);
            }
        }
    }

    // Supprimer les étapes existantes si la recette existe déjà (éviter les doublons)
    $stmt = $pdo->prepare("DELETE FROM Etape WHERE IdRecette = ?");
    $stmt->execute([$recetteId]);

    // Insérer les étapes mises à jour
    if (!empty($data['etapes'])) {
        foreach ($data['etapes'] as $step) {
            $stmt = $pdo->prepare("INSERT INTO Etape (IdRecette, NumeroEtape, DescriptionEtape) VALUES (?, ?, ?)");
            $stmt->execute([$recetteId, $step['numero'], $step['description']]);
        }
    }

    $pdo->commit(); // Valider la transaction
    echo json_encode(["success" => true, "recipeId" => $recetteId]);

} catch (Exception $e) {
    $pdo->rollBack(); // Annuler la transaction en cas d'erreur
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
?>
