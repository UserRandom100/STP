<?php
/**
 * DAO pour la classe Recipe
 */

include_once(__DIR__ . "/../recipe.class.php");
include_once(__DIR__ . "/DAO.interface.php");

class RecipeDAO implements DAO
{

    /**
     * Recherche une recette par son ID
     * 
     * @param int $id L'ID de la recette à rechercher
     * @return Recipe|null La recette trouvée ou null si non trouvé
     */
    static public function findById(int $id): ?object
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD. " . $e->getMessage());
        }

        try {
            $recipe = null;
            // Préparer la requête pour rechercher la recette par ID
            $requete = $connexion->prepare("SELECT * FROM Recipe WHERE id = :id");

            if ($requete === false) {
                throw new Exception("Échec de la préparation de la requête : " . implode(", ", $connexion->errorInfo()));
            }

            $requete->bindParam(':id', $id, PDO::PARAM_INT);
            $requete->execute();

            if ($requete->rowCount() != 0) {
                $enr = $requete->fetch();
                $recipe = new Recipe(
                    $enr['id'],
                    $enr['title'],
                    $enr['description'],
                    $enr['difficulty'],
                    $enr['prepTime'],
                    $enr['id_creator'],
                    $enr['imagePath'], // Ajout de l'image path
                    $enr['imageAndroid'] // Ajout de l'image Android
                );
            }

            $requete->closeCursor();
            ConnexionBD::close();

            return $recipe;
        } catch (Exception $e) {
            throw new Exception("Échec lors de la récupération de la recette : " . $e->getMessage());
        }
    }

    static public function findByUser(int $id)
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $listRecettes = null;
        $requete = $connexion->prepare("SELECT * FROM Recipe WHERE id_creator = :id");
        $requete->bindParam(':id', $id, PDO::PARAM_INT);
        $requete->execute();

        if ($requete->rowCount() != 0) {
            $listRecettes = $requete->fetchAll();
        } else {
            $listRecettes = "pas de recettes creer";
        }

        ConnexionBD::close();
        return $listRecettes;
    }

    /**
     * Retourne une liste de toutes les recettes
     * 
     * @return array Une liste de toutes les recettes
     */
    static public function findAll(): array
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipes = [];
        $requete = $connexion->prepare("SELECT * FROM Recipe");
        $requete->execute();

        foreach ($requete as $enr) {
            $recipes[] = new Recipe(
                $enr['id'],
                $enr['title'],
                $enr['description'],
                $enr['difficulty'],
                $enr['prepTime'],
                $enr['id_creator'],
                $enr['imagePath'], // Ajout de l'image path
                $enr['imageAndroid'] // Ajout de l'image Android
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $recipes;
    }

    /**
     * Recherche des recettes en utilisant un filtre (par exemple dans le titre ou la description)
     * 
     * @param string $filter Le filtre à appliquer (par exemple une clause WHERE)
     * @return array Une liste de recettes correspondant au filtre
     */
    static public function findByDescription(string $filter): array
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipes = [];
        $requete = $connexion->prepare("SELECT * FROM Recipe WHERE description LIKE :filter");
        $requete->bindValue(':filter', '%' . $filter . '%', PDO::PARAM_STR);
        $requete->execute();

        foreach ($requete as $enr) {
            $recipes[] = new Recipe(
                $enr['id'],
                $enr['title'],
                $enr['description'],
                $enr['difficulty'],
                $enr['prepTime'],
                $enr['id_creator'],
                $enr['imagePath'], // Ajout de l'image path
                $enr['imageAndroid'] // Ajout de l'image Android
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $recipes;
    }

    /**
     * Insère une recette dans la base de données
     * 
     * @param Recipe $recipe L'objet à insérer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function save(object $recipe): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $title = $recipe->getTitle();
        $description = $recipe->getDescription();
        $difficulty = $recipe->getDifficulty();
        $prepTime = $recipe->getPrepTime();
        $id_creator = $recipe->getIdCreator();
        $imagePath = $recipe->getImagePath(); // Récupération du chemin de l'image
        $imageData = $recipe->getImageAndroid(); // Récupération des données binaires de l'image

        if ($imageData != null) {

            // Vérifier que le fichier existe
            if (!file_exists($imageData)) {
                throw new Exception("Fichier serveur imageData introuvable : " . $imageData);
            }

            // Lire le contenu de l'image
            $imageData = file_get_contents($imageData);
            if ($imageData === false) {
                throw new Exception("Impossible de lire le fichier image.");
            }
        }


        // Convertir le contenu binaire en une chaîne hexadécimale, préfixée par "0x"
        $imageDataHex = "0x" . strtoupper(bin2hex($imageData));

        // Préparer la requête d'insertion
        // Note : la colonne imagePath dans la base de données est de type BLOB ou VARBINARY
        // Construire la requête SQL en insérant le littéral hexadécimal directement (sans guillemets)
        $requete = $connexion->prepare(
            "INSERT INTO Recipe (title, description, difficulty, prepTime, id_creator, imagePath, imageAndroid)
             VALUES (:title, :description, :difficulty, :prepTime, :id_creator, :imagePath, :imageAndroid)"
        );

        // Liaison des paramètres
        $requete->bindParam(':title', $title, PDO::PARAM_STR);
        $requete->bindParam(':description', $description, PDO::PARAM_STR);
        $requete->bindParam(':difficulty', $difficulty, PDO::PARAM_INT);
        $requete->bindParam(':prepTime', $prepTime, PDO::PARAM_INT);
        $requete->bindParam(':id_creator', $id_creator, PDO::PARAM_INT);
        $requete->bindParam(':imagePath', $imagePath, PDO::PARAM_STR); // Liaison du chemin de l'image
        $requete->bindParam(':imageAndroid', $imageData, PDO::PARAM_LOB);


        $success = $requete->execute();
        if ($success) {
            // Récupère l'ID de la recette après l'insertion
            $recipe->setId((int) $connexion->lastInsertId());
        }

        ConnexionBD::close();
        return $success;
    }

    /**
     * Met à jour une recette dans la base de données
     * 
     * @param Recipe $recipe L'objet à modifier
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function update(object $recipe): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $id = $recipe->getId();
        $title = $recipe->getTitle();
        $description = $recipe->getDescription();
        $difficulty = $recipe->getDifficulty();
        $prepTime = $recipe->getPrepTime();
        $id_creator = $recipe->getIdCreator();
        $imagePath = $recipe->getImagePath(); // Récupération du chemin de l'image

        $requete = $connexion->prepare(
            "UPDATE Recipe 
         SET title = :title, description = :description, difficulty = :difficulty,
             prepTime = :prepTime, id_creator = :id_creator, imagePath = :imagePath
         WHERE id = :id"
        );

        // Liaison des paramètres
        $requete->bindParam(':id', $id, PDO::PARAM_INT);
        $requete->bindParam(':title', $title, PDO::PARAM_STR);
        $requete->bindParam(':description', $description, PDO::PARAM_STR);
        $requete->bindParam(':difficulty', $difficulty, PDO::PARAM_INT);
        $requete->bindParam(':prepTime', $prepTime, PDO::PARAM_INT);
        $requete->bindParam(':id_creator', $id_creator, PDO::PARAM_INT);
        $requete->bindParam(':imagePath', $imagePath, PDO::PARAM_STR);

        $success = $requete->execute();
        ConnexionBD::close();
        return $success;
    }


    /**
     * Supprime une recette de la base de données
     * 
     * @param Recipe $recipe L'objet à supprimer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function delete(object $recipe): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }
        try {
            $id = $recipe->getId();

            $requete = $connexion->prepare("DELETE FROM Recipe WHERE id = :id");

            // Liaison du paramètre pour l'ID de la recette
            $requete->bindParam(':id', $id, PDO::PARAM_INT);

            $success = $requete->execute();
            ConnexionBD::close();
            return $success;
        } catch (Exception $e) {
            throw new Exception('Erreur', $e->getMessage());
        }
    }

    /**
     * Recherche une recette par un email (non applicable ici, retour null)
     * 
     * @param string $email L'email à rechercher
     * @return object|null Retourne null car cette fonctionnalité n'est pas applicable
     */
    static public function findByEmail(string $email): ?object
    {
        return null; // Non applicable pour les recettes
    }

    /**
     * Vérifie si une recette existe par un email (non applicable ici, retour false)
     * 
     * @param string $email L'email à vérifier
     * @return bool Retourne false car cette fonctionnalité n'est pas applicable
     */
    static public function existsByEmail(string $email): bool
    {
        return false; // Non applicable pour les recettes
    }

    public function getRecipesPaginated($limit, $offset)
    {
        try {
            $connexion = ConnexionBD::getInstance();

            $query = "
            SELECT id, title, description, difficulty, prepTime
            FROM Recipe
            ORDER BY id
            LIMIT :limit OFFSET :offset
        ";

            $stmt = $connexion->prepare($query);
            $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ConnexionBD::close();
            return $results;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la pagination des recettes : " . $e->getMessage());
        }
    }





}
?>