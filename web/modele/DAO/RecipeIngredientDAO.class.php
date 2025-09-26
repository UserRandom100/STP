<?php
/**
 * DAO pour la classe RecipeIngredient
 */

include_once(__DIR__ . "/../recipeIngredient.class.php");
include_once(__DIR__ . "/DAO.interface.php");

class RecipeIngredientDAO implements DAO
{

    /**
     * Recherche une association recette-ingrédient par son ID de recette et son ID d'ingrédient
     * 
     * @param int $recipeId L'ID de la recette
     * @param int $ingredientId L'ID de l'ingrédient
     * @return RecipeIngredient|null L'association trouvée ou null si non trouvée
     */
    static public function findById(int $id): ?object
    {
        return null; // Non applicable pour l'ID unique
    }

    /**
     * Retourne une liste de toutes les associations recette-ingrédient
     * 
     * @return array Une liste de toutes les associations
     */
    static public function findAll(): array
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeIngredients = [];
        $requete = $connexion->prepare("SELECT * FROM RecipeIngredient");
        $requete->execute();

        foreach ($requete as $enr) {
            $recipeIngredients[] = new RecipeIngredient(
                $enr['recipeId'],
                $enr['ingredientId'],
                $enr['quantity'],
                $enr['unitOfMeasure']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $recipeIngredients;
    }

    /**
     * Recherche des associations recette-ingrédient en utilisant un filtre sur la quantité ou l'unité de mesure
     * 
     * @param string $filter Le filtre à appliquer (par exemple, une clause WHERE)
     * @return array Une liste d'associations correspondant au filtre
     */
    static public function findByDescription(string $filter): array
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeIngredients = [];
        $requete = $connexion->prepare("SELECT * FROM RecipeIngredient WHERE unitOfMeasure LIKE :filter OR quantity LIKE :filter");
        $requete->bindValue(':filter', '%' . $filter . '%', PDO::PARAM_STR);
        $requete->execute();

        foreach ($requete as $enr) {
            $recipeIngredients[] = new RecipeIngredient(
                $enr['recipeId'],
                $enr['ingredientId'],
                $enr['quantity'],
                $enr['unitOfMeasure']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $recipeIngredients;
    }

    /**
     * Recherche des associations recette-ingrédient en utilisant le id de la recette qui contien ces ingrediant
     * 
     * @param int $idRecipe Le id de la recatte de qui on cherche les ingredient
     * @return array Une liste d'associations correspondant a l<id
     */
    static public function findByIdRecipe(int $idRecipe): array
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeIngredients = [];
        $requete = $connexion->prepare("SELECT * FROM RecipeIngredient WHERE recipeId = :recipId");
        $requete->bindValue(':recipId', $idRecipe, PDO::PARAM_INT);
        $requete->execute();

        foreach ($requete as $enr) {
            $recipeIngredients[] = new RecipeIngredient(
                $enr['recipeId'],
                $enr['ingredientId'],
                $enr['quantity'],
                $enr['unitOfMeasure']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $recipeIngredients;
    }

    /**
     * Recherche une association recette-ingrédient par l'email (non applicable ici, retourne null)
     * 
     * @param string $email L'email à rechercher
     * @return object|null Retourne null, car il n'y a pas d'email pour une recette-ingrédient
     */
    static public function findByEmail(string $email): ?object
    {
        return null; // Non applicable pour RecipeIngredient
    }

    /**
     * Vérifie si une association recette-ingrédient existe par l'email (non applicable ici, retourne false)
     * 
     * @param string $email L'email à vérifier
     * @return bool Retourne false, car il n'y a pas d'email pour une recette-ingrédient
     */
    static public function existsByEmail(string $email): bool
    {
        return false; // Non applicable pour RecipeIngredient
    }

    /**
     * Insère une association recette-ingrédient dans la base de données
     * 
     * @param RecipeIngredient $recipeIngredient L'objet à insérer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function save(object $recipeIngredient): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeId = $recipeIngredient->getRecipeId();
        $ingredientId = $recipeIngredient->getIngredientId();
        $quantity = $recipeIngredient->getQuantity();
        $unitOfMesure = $recipeIngredient->getUnitOfMeasure();

        $requete = $connexion->prepare(
            "INSERT INTO RecipeIngredient (recipeId, ingredientId, quantity, unitOfMeasure)
             VALUES (:recipeId, :ingredientId, :quantity, :unitOfMeasure)"
        );

        // Liaison des paramètres
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $requete->bindParam(':ingredientId', $ingredientId, PDO::PARAM_INT);
        $requete->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $requete->bindParam(':unitOfMeasure', $unitOfMesure, PDO::PARAM_STR);

        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }

    /**
     * Met à jour une association recette-ingrédient dans la base de données
     * 
     * @param RecipeIngredient $recipeIngredient L'objet à modifier
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function update(object $recipeIngredient): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeId = $recipeIngredient->getRecipeId();
        $ingredientId = $recipeIngredient->getIngredientId();
        $quantity = $recipeIngredient->getQuantity();
        $unitOfMesure = $recipeIngredient->getUnitOfMeasure();

        $requete = $connexion->prepare(
            "UPDATE RecipeIngredient 
             SET quantity = :quantity, unitOfMeasure = :unitOfMeasure
             WHERE recipeId = :recipeId AND ingredientId = :ingredientId"
        );

        // Liaison des paramètres
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $requete->bindParam(':ingredientId', $ingredientId, PDO::PARAM_INT);
        $requete->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $requete->bindParam(':unitOfMeasure', $unitOfMesure, PDO::PARAM_STR);

        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }

    /**
     * Supprime une association recette-ingrédient de la base de données
     * 
     * @param RecipeIngredient $recipeIngredient L'objet à supprimer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function delete(object $recipeIngredient): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $requete = $connexion->prepare(
            "DELETE FROM RecipeIngredient WHERE recipeId = :recipeId AND ingredientId = :ingredientId"
        );

        $recipeId = $recipeIngredient->getRecipeId();
        $ingredientId = $recipeIngredient->getIngredientId();

        // Liaison des paramètres
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $requete->bindParam(':ingredientId', $ingredientId, PDO::PARAM_INT);

        $success = $requete->execute();
        ConnexionBD::close();
        return $success;
    }

    /**
     * supprime toute les ingredients pour une recette
     * 
     * @param int $recipeId The recipe ID
     * @return bool 
     */
    static public function deleteAllForRecipe(int $recipeId): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $requete = $connexion->prepare(
            "DELETE FROM RecipeIngredient WHERE recipeId = :recipeId"
        );

        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }
}
?>