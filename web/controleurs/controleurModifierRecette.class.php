<?php
require_once __DIR__ . '/../modele/DAO/RecipeFullDAO.class.php';
require_once __DIR__ . '/../modele/DAO/RecipeDAO.class.php';
require_once __DIR__ . '/../modele/DAO/RecipeIngredientDAO.class.php';
require_once __DIR__ . '/../modele/DAO/StepDAO.class.php';



class ModifierRecette extends Controleur
{
    public function __construct() {
        parent::__construct();
    }

    public function executerAction() {
        // Si c'est une sauvegarde, on appelle la méthode dédiée
        if ($_GET['action'] == "sauvegarderModificationRecette") {
            $this->sauvegarderModification();
            return "modification_recette.php"; // pas besoin de vue, redirection déjà faite
        }
    
        // Sinon, c'est l'affichage de la page de modification
        $idRecette = filter_input(INPUT_GET, 'idRecette', FILTER_VALIDATE_INT);
        $userId = $_SESSION['userId'] ?? null;
    
        if (!$idRecette || !$userId) {
            header('Location: ?action=voirMesRecettes');
            exit;
        }
    
        $data = RecipeFullDAO::findFullRecipeById($idRecette);
    
        if (!$data || $data['recipe']->getIdCreator() !== $userId) {
            header('Location: ?action=voirMesRecettes');
            exit;
        }
    
        $this->donnees['recette'] = $data['recipe'];
        $this->donnees['ingredients'] = $data['ingredients'];
        $this->donnees['steps'] = $data['steps'];
    
        return 'modification_recette';
    }
    

    public function sauvegarderModification()
    {
        $userId = $_SESSION['userId'] ?? null;
        $id = filter_input(INPUT_POST, 'idRecette', FILTER_VALIDATE_INT);

        if (!$userId || !$id) {
            header('Location: ?action=connexion');
            exit;
        }

        $recette = RecipeDAO::findById($id);
        if (!$recette || $recette->getIdCreator() !== $userId) {
            header('Location: ?action=voirMesRecettes');
            exit;
        }

        try {
            // MAJ info recette
            $recette->setTitle($_POST['recipe-name']);
            $recette->setDescription($_POST['description']);
            $recette->setDifficulty((int)$_POST['difficulty']);
            $recette->setPrepTime((int)$_POST['prep-time']);

            // if (!empty($_FILES['recipe-image']['name'])) {
            //     $uploadDir = 'assets/images/recipes/';
            //     $fileName = basename($_FILES['recipe-image']['name']);
            //     $uploadPath = $uploadDir . $fileName;

            //     if (move_uploaded_file($_FILES['recipe-image']['tmp_name'], $uploadPath)) {
            //         $recette->setImageAndroid($uploadPath);
            //     } else {
            //         throw new Exception("Échec de l'upload de l'image");
            //     }
            // }


            if (isset($_FILES['recipe-image']) && $_FILES['recipe-image']['error'] === UPLOAD_ERR_OK) {
                //si cest un admin qui creer la recette un userId entrer par celui ci sera envoyer
                if (!isset($_POST['userId']) || empty($_POST['userId'])) {
                    $userId = $_SESSION['userId'];
                } else {
                    $userId = $_POST['userId'];
                    $_POST['userId'] = null;
                }
                
                
                
                $imageTmpPath = $_FILES['recipe-image']['tmp_name'];
                $imageName = basename($_FILES['recipe-image']['name']);
                $imageName = time() . "_" . $imageName; // Ajout d'un timestamp pour éviter les conflits de nom

                //$uploadDir = 'assets/images/recipes/';
                
                // Définir le dossier de destination pour l'image sur le serveur
                $uploadDir = __DIR__ . '/../uploads/images/' . $userId;
                
                
                // Créer le dossier si il n'existe pas
                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0755, true)) {
                        //error_log("Failed to create directory: $uploadDir");
                        http_response_code(500);
                        //echo json_encode(array("message" => "Erreur lors de la création du dossier pour l'image."));
                        throw new Exception("Erreur lors de la création du dossier pour l'image.");

                    }
                }
                
                
                $uploadPath = $uploadDir . '/' . $imageName;
            
                // Vérifier type MIME
                $imageType = mime_content_type($imageTmpPath);
                $allowedTypes = ['image/jpeg'];
            
                if (!in_array($imageType, $allowedTypes)) {
                    throw new Exception("Format d’image non supporté ($imageType). Formats acceptés : JPEG, PNG, WEBP.");
                }
            
                if (move_uploaded_file($imageTmpPath, $uploadPath)) {
                    $recette->setImageAndroid($uploadPath);
                } else {
                    throw new Exception("Échec de l'upload de l'image.");
                }
            }
            

            RecipeDAO::update($recette);

            // MAJ des ingrédients
            RecipeIngredientDAO::deleteAllForRecipe($id);
            foreach ($_POST['ingredients'] ?? [] as $ingredient) {
                $ri = new RecipeIngredient(
                    $id,
                    (int)$ingredient['id'],
                    (int)$ingredient['quantity'],
                    $ingredient['unit']
                );
                RecipeIngredientDAO::save($ri);
            }

            // MAJ des étapes
            StepDAO::deleteAllForRecipe($id);
            $stepIndex = 1;
            foreach ($_POST['steps'] ?? [] as $desc) {
                $step = new Step($id, $stepIndex++, $desc);
                StepDAO::save($step);
            }

            $_GET['recipeId'] = $id;
            header("Location: ?action=informationRecette");
            exit;

        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur lors de la sauvegarde : " . $e->getMessage();
            header("Location: ?action=modifierRecette&idRecette=$id");
            exit;
            
        }
    }
}
