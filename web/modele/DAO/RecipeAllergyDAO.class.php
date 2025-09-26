<?php
/**
 * DAO pour la classe RecipeAllergy
 */

include_once(__DIR__ . "/../recipeAllergy.class.php");
include_once(__DIR__ . "/DAO.interface.php");

class RecipeAllergyDAO implements DAO {

    /**
     * Recherche une relation recette-allergie par l'ID combiné recette + allergie
     * 
     * @param int $id L'ID combiné de la recette et de l'allergie
     * @return RecipeAllergy|null L'objet trouvé ou null si non trouvé
     */
    static public function findById(int $id): ?object {
        return null; // Pas applicable pour RecipeAllergy
    }

    /**
     * Retourne une liste de toutes les relations recette-allergie
     * 
     * @return array Une liste contenant tous les objets RecipeAllergy
     */
    static public function findAll(): array {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeAllergies = [];
        $requete = $connexion->prepare("SELECT * FROM RecipeAllergy");
        $requete->execute();

        foreach ($requete as $enr) {
            $recipeAllergies[] = new RecipeAllergy(
                $enr['recipeId'],
                $enr['allergyId']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $recipeAllergies;
    }

    /**
     * Recherche les relations recette-allergie en fonction d'un filtre
     * 
     * @param string $filter Le filtre à appliquer (ex: l'ID de la recette ou de l'allergie)
     * @return array Une liste d'objets RecipeAllergy correspondant au filtre
     */
    static public function findByDescription(string $filter): array {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeAllergies = [];
        $requete = $connexion->prepare("SELECT * FROM RecipeAllergy WHERE recipeId = :filter OR allergyId = :filter");
        $requete->bindValue(':filter', $filter, PDO::PARAM_INT);
        $requete->execute();

        foreach ($requete as $enr) {
            $recipeAllergies[] = new RecipeAllergy(
                $enr['recipeId'],
                $enr['allergyId']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $recipeAllergies;
    }

    /**
     * Recherche une relation recette-allergie par email (non applicable ici)
     * 
     * @param string $email L'email de l'utilisateur
     * @return object|null L'objet trouvé ou null si non trouvé
     */
    static public function findByEmail(string $email): ?object {
        return null; // Pas applicable pour RecipeAllergy
    }

    /**
     * Vérifie si une relation recette-allergie existe via l'email (non applicable ici)
     * 
     * @param string $email L'email à vérifier
     * @return bool Retourne false car l'email n'est pas utilisé ici
     */
    static public function existsByEmail(string $email): bool {
        return false; // Pas applicable pour RecipeAllergy
    }

    /**
     * Insère une relation recette-allergie dans la base de données
     * 
     * @param object $object L'objet RecipeAllergy à insérer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function save(object $object): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeId = $object->getRecipeId();
        $allergyId = $object->getAllergyId();

        $requete = $connexion->prepare(
            "INSERT INTO RecipeAllergy (recipeId, allergyId)
             VALUES (:recipeId, :allergyId)"
        );

        // Liaison des paramètres
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $requete->bindParam(':allergyId', $allergyId, PDO::PARAM_INT);

        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }

    /**
     * Met à jour une relation recette-allergie dans la base de données
     * 
     * @param object $object L'objet RecipeAllergy à modifier
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function update(object $object): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeId = $object->getRecipeId();
        $allergyId = $object->getAllergyId();

        $requete = $connexion->prepare(
            "UPDATE RecipeAllergy
             SET allergyId = :allergyId
             WHERE recipeId = :recipeId"
        );

        // Liaison des paramètres
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $requete->bindParam(':allergyId', $allergyId, PDO::PARAM_INT);

        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }

    /**
     * Supprime une relation recette-allergie dans la base de données
     * 
     * @param object $object L'objet RecipeAllergy à supprimer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function delete(object $object): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeId = $object->getRecipeId();
        $allergyId = $object->getAllergyId();

        $requete = $connexion->prepare(
            "DELETE FROM RecipeAllergy WHERE recipeId = :recipeId AND allergyId = :allergyId"
        );

        // Liaison des paramètres
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $requete->bindParam(':allergyId', $allergyId, PDO::PARAM_INT);

        $success = $requete->execute();
        ConnexionBD::close();
        return $success;
    }
}
?>
