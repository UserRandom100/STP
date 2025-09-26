<?php
/**
 * DAO pour la classe UserSavedRecipe
 */

include_once(__DIR__ . "/../userSavedRecipe.class.php");
include_once(__DIR__ . "/DAO.interface.php");

class UserSavedRecipeDAO implements DAO {

    /**
     * Recherche une recette sauvegardée par l'ID de l'utilisateur et l'ID de la recette
     * 
     * @param int $id L'ID combiné utilisateur + recette (clé primaire)
     * @return UserSavedRecipe|null L'objet trouvé ou null si non trouvé
     */
    static public function findById(int $id): ?object {
        return null; // Pas applicable pour UserSavedRecipe
    }

    /**
     * Retourne une liste de toutes les recettes sauvegardées par les utilisateurs
     * 
     * @return array Une liste contenant tous les objets UserSavedRecipe
     */
    static public function findAll(): array {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $userSavedRecipes = [];
        $requete = $connexion->prepare("SELECT * FROM UserSavedRecipe");
        $requete->execute();

        foreach ($requete as $enr) {
            $userSavedRecipes[] = new UserSavedRecipe(
                $enr['userId'],
                $enr['recipeId']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $userSavedRecipes;
    }

    /**
     * Recherche les recettes sauvegardées par un utilisateur spécifique
     * 
     * @param string $filter Le filtre à appliquer (ex. l'ID d'un utilisateur)
     * @return array Une liste d'objets UserSavedRecipe correspondant au filtre
     */
    static public function findByDescription(string $filter): array {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $userSavedRecipes = [];
        $requete = $connexion->prepare("SELECT * FROM UserSavedRecipe WHERE userId = :userId");
        $requete->bindValue(':userId', $filter, PDO::PARAM_INT);
        $requete->execute();

        foreach ($requete as $enr) {
            $userSavedRecipes[] = new UserSavedRecipe(
                $enr['userId'],
                $enr['recipeId']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $userSavedRecipes;
    }

    /**
     * Recherche une recette sauvegardée par l'email de l'utilisateur (non applicable ici)
     * 
     * @param string $email L'email de l'utilisateur
     * @return object|null L'objet trouvé ou null si non trouvé
     */
    static public function findByEmail(string $email): ?object {
        return null; // Pas applicable pour UserSavedRecipe
    }

    /**
     * Vérifie si une recette a été sauvegardée par un utilisateur via l'email (non applicable ici)
     * 
     * @param string $email L'email de l'utilisateur
     * @return bool Retourne false, car l'email n'est pas utilisé dans cette classe
     */
    static public function existsByEmail(string $email): bool {
        return false; // Pas applicable pour UserSavedRecipe
    }

    /**
     * Insère une recette sauvegardée par un utilisateur dans la base de données
     * 
     * @param object $object L'objet UserSavedRecipe à insérer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function save(object $object): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $userId = $object->getUserId();
        $recipeId = $object->getRecipeId();

        $requete = $connexion->prepare(
            "INSERT INTO UserSavedRecipe (userId, recipeId)
             VALUES (:userId, :recipeId)"
        );

        // Liaison des paramètres
        $requete->bindParam(':userId', $userId, PDO::PARAM_INT);
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);

        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }

    /**
     * Met à jour une recette sauvegardée par un utilisateur dans la base de données
     * 
     * @param object $object L'objet UserSavedRecipe à modifier
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function update(object $object): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        
        $userId = $object->getUserId();
        $recipeId = $object->getRecipeId();

        $requete = $connexion->prepare(
            "UPDATE UserSavedRecipe 
             SET recipeId = :recipeId 
             WHERE userId = :userId"
        );

        // Liaison des paramètres
        $requete->bindParam(':userId', $userId, PDO::PARAM_INT);
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);


        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }

    /**
     * Supprime une recette sauvegardée par un utilisateur dans la base de données
     * 
     * @param object $object L'objet UserSavedRecipe à supprimer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function delete(object $object): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $userId = $object->getUserId();
        $recipeId = $object->getRecipeId();
        
        $requete = $connexion->prepare(
            "DELETE FROM UserSavedRecipe WHERE userId = :userId AND recipeId = :recipeId"
        );

        // Liaison des paramètres
        $requete->bindParam(':userId', $userId, PDO::PARAM_INT);
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);


        $success = $requete->execute();
        ConnexionBD::close();
        return $success;
    }
}
?>
