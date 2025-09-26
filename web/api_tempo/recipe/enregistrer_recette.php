<?php


// ini_set('display_errors', 1);
// error_reporting(E_ALL);
// error_log("ID utilisateur connecté : " . $id_creator);
// HEADERS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Démarrer la session pour accéder à la variable $_SESSION
session_start();

// Donnee de débogage pour afficher les données reçues
// if (isset($_FILES['image'])) {
//     var_dump($_FILES['image']);  // Afficher les informations sur l'image téléchargée
// }


// INCLUSIONS
include_once(__DIR__ . "/../../modele/DAO/RecipeDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/IngredientDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/StepDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/RecipeIngredientDAO.class.php");
include_once(__DIR__ . "/../../modele/DAO/RecipeAllergyDAO.class.php");
include_once(__DIR__ . "/../../modele/recipe.class.php");
include_once(__DIR__ . "/../../modele/step.class.php");
include_once(__DIR__ . "/../../modele/recipeIngredient.class.php");
include_once(__DIR__ . "/../../modele/recipeAllergy.class.php");

try {

    // Vérification de la requête POST (pas de JSON ici puisque FormData est utilisé)
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        echo json_encode(array("message" => "Méthode non autorisée."));
        exit();
    }

    // Récupération des données classiques de la recette
    $title = $_POST['name'];
    $description = $_POST['description'] ?? null;   
    $difficulty = isset($_POST['difficulty']) ? intval($_POST['difficulty']) : null;
    $prepTime = isset($_POST['prepTime']) ? intval($_POST['prepTime']) : null;

    //si cest un admin qui creer la recette un userId entrer par celui ci sera envoyer
    if(!isset($_POST['userId'])||empty($_POST['userId'])){
        $userId = $_SESSION['userId'];
    }else{
        $userId = $_POST['userId'];
        $_POST['userId'] = null;
    }
    // Création de la recette
    $recipe = new Recipe(null, $title, $description, $difficulty, $prepTime, $userId, null, null); // On ne met pas encore d'image, on va la gérer après
    $recipeSaved = RecipeDAO::save($recipe); // Appel à save() qui va insérer et permettre par la suite de récupérer l'ID de la recette

    if (!$recipeSaved) {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Erreur lors de l'enregistrement de la recette."));
        exit();
    }

    // Récupérer l'ID de la recette
    $recipeId = $recipe->getId();

    // Sauvegarder les étapes
    $steps = json_decode($_POST['steps'], true);
    foreach ($steps as $step) {
        $stepObj = new Step($recipeId, $step['numero'], $step['description']);
        StepDAO::save($stepObj);
    }

    // Sauvegarder les ingrédients
    $ingredientIds = [];
    $ingredients = json_decode($_POST['ingredients'], true);
    foreach ($ingredients as $ingredient) {
        // Recherche si l'ingrédient existe déjà par son nom
        $ingredientObj = IngredientDAO::findByName($ingredient['ingredient']);

        if (!$ingredientObj) {
            // Si l'ingrédient n'existe pas, crée un nouvel ingrédient
            $newIngredient = new Ingredient(null, $ingredient['ingredient'], $ingredient['unitOfMeasure']);

            // Sauvegarde le nouvel ingrédient dans la base de données
            if (IngredientDAO::save($newIngredient)) {
                // Récupérer l'ID du nouvel ingrédient après l'insertion
                $ingredientObj = $newIngredient;
            } else {
                
                continue; // On passe à l'itération suivante
            }
        }


        // Ajoute une vérification pour ne pas insérer deux fois le même ingredientId pour une recette
        $ingredientId = $ingredientObj->getId();

        if (in_array($ingredientId, $ingredientIds)) {
            continue; // déjà ajouté
        }

        $ingredientIds[] = $ingredientId;

        // Maintenant que tu as l'ID de l'ingrédient (qu'il soit existant ou nouvellement ajouté),
        // on peut associer cet ingrédient à la recette
        $recipeIngredient = new RecipeIngredient($recipeId, $ingredientObj->getId(), $ingredient['unite'], $ingredient['unitOfMeasure']);
        RecipeIngredientDAO::save($recipeIngredient);
    }



    // Initialisation des variables pour l'image
    $imagePathRelative = null;
    $imagePathLocal = null;

    // POUR PAGE RECHERCHE_RECETTE (DÉCOUVRIR)
    $imageInternet = $_POST['image-web'] ?? null;   
    if(isset($_POST['image-web']) && !empty($_POST['image-web'])){
        $recipe->setImagePath($imageInternet); // Met à jour l'objet recette avec le chemin relatif
       // $recipe->setImageAndroid($imageInternet); // Met à jour l'objet recette avec le chemin local (pour le débogage)
        RecipeDAO::update($recipe); // Met à jour la recette avec le chemin relatif de l'image


        // POUR PAGE CREATION_RECETTE
    } else if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) { // Vérification de l'image et stockage
        // Définir le dossier de destination pour l'image sur le serveur
        $uploadDir = __DIR__ . '/../../uploads/images/' . $userId;


        // Créer le dossier si il n'existe pas
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                //error_log("Failed to create directory: $uploadDir");
                http_response_code(500);
                echo json_encode(array("message" => "Erreur lors de la création du dossier pour l'image."));
                exit();
            }
        }

        // Extraire le nom du fichier et ajouter un identifiant unique
        $imageName = basename($_FILES['image']['name']);  // Récupère le nom de l'image
        $imageName = time() . "_" . $imageName;  // Ajouter un timestamp pour éviter les conflits de noms

        // Chemin complet pour le fichier local
        $imagePathLocal = $uploadDir . '/' . $imageName;

        // Vérifier si le fichier est une image valide
        $imageType = mime_content_type($_FILES['image']['tmp_name']);
        $allowedTypes = ['image/jpeg'];

        if (in_array($imageType, $allowedTypes)) {
            // Déplacer l'image vers le dossier sur le serveur
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePathLocal)) {
                // Définir le chemin relatif de l'image avec la variable $userId
                $imagePathRelative = "/SwipeTonSouper_MySQL/uploads/images/" . $userId . "/" . $imageName;

                // Sauvegarder le chemin dans la base de données
                // $recipe->setImagePath($imagePathRelative); // Met à jour l'objet recette avec le chemin relatif
                $recipe->setImageAndroid($imagePathLocal); // Met à jour l'objet recette avec le chemin local (pour le débogage)
                RecipeDAO::update($recipe); // Met à jour la recette avec le chemin relatif de l'image

            } else {
                // Erreur lors de l'upload de l'image
                http_response_code(500); // Internal Server Error
                echo json_encode(array("message" => "Erreur lors de l'upload de l'image."));
            }
        } else {
            // Si le fichier n'est pas une image valide
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Le fichier téléchargé n'est pas une image valide (JPEG seulement)."));
        }
    } else {
        // Si aucun fichier n'est sélectionné ou une erreur d'upload
        http_response_code(400); // Bad Request
        echo json_encode(array("message" => "Aucun fichier image téléchargé ou erreur dans l'upload."));
    }



    // Réponse de succès
    http_response_code(201); // Created
    echo json_encode(array(
        "recipeData" => [
            'name' => $title,
            'description' => $description,
            'difficulty' => $difficulty,
            'prepTime' => $prepTime,
            'ingredients' => $ingredients,
            'steps' => $steps
            // ,
            // 'imagePathRelative' => $imagePathRelative, // Chemin de l'image, si elle a été téléchargée
            // 'imagePathLocal' => $imagePathLocal // Chemin local de l'image, pour le débogage
        ],
        "message" => "Recette enregistrée avec succès.",
        "recipeId" => $recipeId
    ));
} catch (Exception $e) {
    // Gestion des erreurs
    http_response_code(500); // Internal Server Error
    echo json_encode(array(
        "recipeData" => $_POST, 
        "message" => "Une erreur est survenue : " . $e->getMessage(),
        "error" => $e->getTraceAsString()
        // ,
        // "imagePathRelative" => $imagePathRelative, // Pour le débogage
        // "imagePathLocal" => $imagePathLocal // Pour le débogage
    ));
}














// // ini_set('display_errors', 1);
// // error_reporting(E_ALL);
// // error_log("ID utilisateur connecté : " . $id_creator);
// // HEADERS
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");
// header("Content-Type: application/json; charset=UTF-8");

// // Démarrer la session pour accéder à la variable $_SESSION
// session_start();

// // INCLUSIONS
// include_once(__DIR__ . "/../../modele/DAO/RecipeDAO.class.php");
// include_once(__DIR__ . "/../../modele/DAO/IngredientDAO.class.php");
// include_once(__DIR__ . "/../../modele/DAO/StepDAO.class.php");
// include_once(__DIR__ . "/../../modele/DAO/RecipeIngredientDAO.class.php");
// include_once(__DIR__ . "/../../modele/DAO/RecipeAllergyDAO.class.php");
// include_once(__DIR__ . "/../../modele/Recipe.class.php");
// include_once(__DIR__ . "/../../modele/Step.class.php");
// include_once(__DIR__ . "/../../modele/RecipeIngredient.class.php");
// include_once(__DIR__ . "/../../modele/RecipeAllergy.class.php");

// try {
//     // Vérification si l'utilisateur est connecté
//     //if (!isset($_SESSION['utilisateurConnecte']) || !$_SESSION['utilisateurConnecte']) {
//    //     http_response_code(401); // Unauthorized
//     //    echo json_encode(array("message" => "Vous devez être connecté pour enregistrer une recette."));
//    //     exit();
//    // }

//     // Récupérer l'ID de l'utilisateur connecté
//     //$id_creator = $_SESSION['userId'];  
//    // echo $id_creator;

//     // Vérification de la requête JSON
//     $inputData = json_decode(file_get_contents('php://input'), true);

//     if (!$inputData) {
//         http_response_code(400); // Bad Request
//         echo json_encode(array("message" => "Données invalides."));
//         exit();
//     }

//     // Récupérer les informations de la recette
//     $title = $inputData['name'];
//     $description = $inputData['description'] ?? null;
//     $difficulty = $inputData['difficulty'];
//     $prepTime = $inputData['prepTime'];
//     $userId = $_SESSION['userId'];


//     // Création de la recette
//     $recipe = new Recipe(null, $title, $description, $difficulty, $prepTime, $userId);
//     $recipeSaved = RecipeDAO::save($recipe); // Appel à save() qui va insérer et permet par la suite de récupérer l'ID de la recette


//     if (!$recipeSaved) {
//         http_response_code(500); // Internal Server Error
//         echo json_encode(array("message" => "Erreur lors de l'enregistrement de la recette."));
//         exit();
//     }

//     // Maintenant que l'ID est assigné, on peut récupérer l'ID de la recette 
//     $recipeId = $recipe->getId();

//     // Sauvegarder les étapes
//     $steps = $inputData['etapes'];
//     foreach ($steps as $step) {
//         $stepObj = new Step($recipeId, $step['numero'], $step['description']);
//         StepDAO::save($stepObj);
//     }


//     // Sauvegarder les ingrédients
//     $ingredients = $inputData['ingredients']; // Structure attendue : [{numero, unite, ingredient, unitOfMeasure}]
//     foreach ($ingredients as $ingredient) {
//         // Recherche de l'ingrédient dans la base de données
//         $ingredientObj = IngredientDAO::findByName($ingredient['ingredient']); // Rechercher par nom d'ingrédient
//         if ($ingredientObj) {
//             // Créer une association recette-ingrédient
//             $recipeIngredient = new RecipeIngredient($recipeId, $ingredientObj->getId(), $ingredient['unite'], $ingredient['unitOfMeasure']);
//             RecipeIngredientDAO::save($recipeIngredient);
//         }
//     }

//     // Sauvegarder les allergies (si elles existent)
//     // if (isset($inputData['allergies'])) {
//     //     $allergies = $inputData['allergies'];
//     //     foreach ($allergies as $allergyId) {
//     //         $recipeAllergy = new RecipeAllergy($recipeId, $allergyId);
//     //         RecipeAllergyDAO::save($recipeAllergy);
//     //     }
//     // }

//     // Réponse de succès
//     http_response_code(201); // Created
//     echo json_encode(array( "recipeData" => $inputData, // données de recette initiales
//                                     "message" => "Recette enregistrée avec succès.", 
//                                     "recipeId" => $recipeId));
// } catch (Exception $e) {
//     // Gestion des erreurs
//     http_response_code(500); // Internal Server Error
//     echo json_encode(array("recipeData" => $inputData,
//                                     "message" => "Une erreur est survenue : " . $e->getMessage()));
// }


?>
