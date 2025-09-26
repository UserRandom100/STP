<?php
/**
 * DAO pour la classe Step
 */

include_once(__DIR__ . "/DAO.interface.php");
include_once(__DIR__ . "/../step.class.php");

class StepDAO implements DAO
{

    /**
     * Recherche une étape par son ID de recette et son numéro d'étape
     * 
     * @param int $recipeId L'ID de la recette
     * @param int $stepNumber Le numéro de l'étape
     * @return Step|null L'étape trouvée ou null si non trouvée
     */
    static public function findById(int $id): ?object
    {
        return null; // Cette méthode n'est pas applicable ici, retour de null
    }

    /**
     * Retourne une liste de toutes les étapes associées à une recette
     * 
     * @param int $recipeId L'ID de la recette
     * @return array Une liste de toutes les étapes
     */
    static public function findAll(): array
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $steps = [];
        $requete = $connexion->prepare("SELECT * FROM Step ORDER BY stepNumber ASC");
        $requete->execute();

        foreach ($requete as $enr) {
            $steps[] = new Step(
                $enr['recipeId'],
                $enr['stepNumber'],
                $enr['stepDescription']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $steps;
    }

    /**
     * Recherche des étapes en utilisant un filtre sur la description
     * 
     * @param string $filter Le filtre à appliquer sur la description des étapes
     * @return array Une liste d'étapes correspondant au filtre
     */
    static public function findByDescription(string $filter): array
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $steps = [];
        $requete = $connexion->prepare("SELECT * FROM Step WHERE stepDescription LIKE :filter ORDER BY stepNumber ASC");
        $requete->bindValue(':filter', '%' . $filter . '%', PDO::PARAM_STR);
        $requete->execute();

        foreach ($requete as $enr) {
            $steps[] = new Step(
                $enr['recipeId'],
                $enr['stepNumber'],
                $enr['stepDescription']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $steps;
    }

    /**
     * Recherche des étapes en utilisant l<id de la recette associer
     * 
     * @param string $idRecipe le id de la recette dont nous recherchon les etapes
     * @return array Une liste d'étapes correspondant au id de la recette
     */
    static public function findByIdRecipe(int $idRecipe): array
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $steps = [];
        $requete = $connexion->prepare("SELECT * FROM Step WHERE recipeId = :recipId");
        $requete->bindValue(':recipId', $idRecipe, PDO::PARAM_INT);
        $requete->execute();

        foreach ($requete as $enr) {
            $steps[] = new Step(
                $enr['recipeId'],
                $enr['stepNumber'],
                $enr['stepDescription']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $steps;
    }

    /**
     * Recherche une étape par l'email (non applicable ici, retourne null)
     * 
     * @param string $email L'email à rechercher
     * @return object|null Retourne null, car il n'y a pas d'email pour une étape
     */
    static public function findByEmail(string $email): ?object
    {
        return null; // Non applicable pour les étapes
    }

    /**
     * Vérifie si une étape existe par l'email (non applicable ici, retourne false)
     * 
     * @param string $email L'email à vérifier
     * @return bool Retourne false, car il n'y a pas d'email pour une étape
     */
    static public function existsByEmail(string $email): bool
    {
        return false; // Non applicable pour les étapes
    }

    /**
     * Insère une étape dans la base de données
     * 
     * @param Step $step L'objet étape à insérer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function save(object $step): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipId = $step->getRecipeId();
        $stepNumber = $step->getStepNumber();
        $stepDescription = $step->getStepDescription();

        $requete = $connexion->prepare(
            "INSERT INTO Step (recipeId, stepNumber, stepDescription)
             VALUES (:recipeId, :stepNumber, :stepDescription)"
        );

        // Liaison des paramètres
        $requete->bindParam(':recipeId', $recipId, PDO::PARAM_INT);
        $requete->bindParam(':stepNumber', $stepNumber, PDO::PARAM_INT);
        $requete->bindParam(':stepDescription', $stepDescription, PDO::PARAM_STR);

        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }

    /**
     * Met à jour une étape dans la base de données
     * 
     * @param Step $step L'objet étape à modifier
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function update(object $step): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipId = $step->getRecipeId();
        $stepNumber = $step->getStepNumber();
        $stepDescription = $step->getStepDescription();

        $requete = $connexion->prepare(
            "UPDATE Step SET stepDescription = :stepDescription
             WHERE recipeId = :recipeId AND stepNumber = :stepNumber"
        );

        // Liaison des paramètres
        $requete->bindParam(':recipeId', $recipId, PDO::PARAM_INT);
        $requete->bindParam(':stepNumber', $stepNumber, PDO::PARAM_INT);
        $requete->bindParam(':stepDescription', $stepDescription, PDO::PARAM_STR);

        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }

    /**
     * Supprime une étape de la base de données
     * 
     * @param Step $step L'objet étape à supprimer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function delete(object $step): bool
    {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $recipeId = $step->getRecipeId();
        $stepNumber = $step->getStepNumber();

        $requete = $connexion->prepare(
            "DELETE FROM Step WHERE recipeId = :recipeId AND stepNumber = :stepNumber"
        );

        // Liaison des paramètres
        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $requete->bindParam(':stepNumber', $stepNumber, PDO::PARAM_INT);

        $success = $requete->execute();
        ConnexionBD::close();
        return $success;
    }

    /**
     * supprime toutes les etapes pour une recette
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
            "DELETE FROM Step WHERE recipeId = :recipeId"
        );

        $requete->bindParam(':recipeId', $recipeId, PDO::PARAM_INT);
        $success = $requete->execute();

        ConnexionBD::close();
        return $success;
    }
}
?>