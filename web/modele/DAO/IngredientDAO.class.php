<?php
/**
 * Description : DAO pour la classe Ingredient et la table Ingredient
 */

include_once(__DIR__ . "/DAO.interface.php");
include_once(__DIR__ . "/../ingredient.class.php");

class IngredientDAO implements DAO {

    /**
     * Recherche un ingrédient par son ID
     * @param int $id L'ID de l'ingrédient à chercher
     * @return Ingredient|null L'ingrédient trouvé ou null si non trouvé
     */
    static public function findById(int $id): ?Ingredient {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $ingredient = null;
        $requete = $connexion->prepare("SELECT * FROM Ingredient WHERE id = :id");
        // Liaison du paramètre pour l'ID
        $requete->bindParam(':id', $id, PDO::PARAM_INT);
        $requete->execute();

        if ($requete->rowCount() != 0) {
            $enr = $requete->fetch();
            $ingredient = new Ingredient(
                $enr['id'], 
                $enr['name'], // 'name' correspond à la colonne de la table Ingredient
                $enr['unitOfMeasure'] // Unité de mesure
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $ingredient;
    }

    /**
     * Retourne une liste de tous les ingrédients
     * @return array Liste des ingrédients
     */
    static public function findAll(): array {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $ingredients = [];
        $requete = $connexion->prepare("SELECT * FROM Ingredient");
        $requete->execute();

        foreach ($requete as $enr) {
            $ingredients[] = new Ingredient(
                $enr['id'], 
                $enr['name'], // 'name' correspond à la colonne de la table Ingredient
                $enr['unitOfMeasure'] // Unité de mesure
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $ingredients;
    }

    /**
     * Recherche un ingrédient par son nom (utilise LIKE)
     * @param string $name Le nom de l'ingrédient à chercher
     * @return Ingredient|null L'ingrédient trouvé ou null si non trouvé
     */
    static public function findByName(string $name): ?Ingredient {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $ingredient = null;
        $requete = $connexion->prepare("SELECT * FROM Ingredient WHERE name LIKE :name");
        // Liaison du paramètre pour le nom avec LIKE
        $name = "%$name%"; // Recherche avec LIKE (pour inclure n'importe quelle occurrence du texte)
        $requete->bindParam(':name', $name, PDO::PARAM_STR);
        $requete->execute();

        if ($requete->rowCount() != 0) {
            $enr = $requete->fetch();
            $ingredient = new Ingredient(
                $enr['id'], 
                $enr['name'], 
                $enr['unitOfMeasure'] // Unité de mesure
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $ingredient;
    }

    /**
     * Recherche un ingrédient par description (dans un champ "name")
     * @param string $filter Le filtre à appliquer sur le nom
     * @return array Tableau d'ingrédients trouvés (ou un tableau vide si aucun trouvé)
     */
    static public function findByDescription(string $filter): array {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $ingredients = [];
        // Recherche dans la colonne "name" (pas de champ description spécifique)
        $requete = $connexion->prepare("SELECT * FROM Ingredient WHERE name LIKE :filter");
        // Ajout des % autour du filtre pour chercher n'importe quelle occurrence du texte
        $filter = "%$filter%";
        $requete->bindParam(':filter', $filter, PDO::PARAM_STR);
        $requete->execute();

        // Parcours des résultats et création d'objets Ingredient
        foreach ($requete as $enr) {
            $ingredients[] = new Ingredient(
                $enr['id'],
                $enr['name'], 
                $enr['unitOfMeasure'] // Unité de mesure
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        // Retourne un tableau vide si aucun ingrédient n'est trouvé
        return $ingredients;
    }

    /**
     * Insère un ingrédient dans la base de données
     * @param Ingredient $ingredient L'ingrédient à insérer
     * @return bool Vrai si l'insertion a réussi, faux sinon
     */
    static public function save(object $ingredient): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $requete = $connexion->prepare(
            "INSERT INTO Ingredient (name, unitOfMeasure) 
             VALUES (:name, :unitOfMeasure)" // 'name' et 'unitOfMeasure' pour correspondre à la table
        );

        // Récupération du nom et de l'unité de mesure de l'ingrédient
        $name = $ingredient->getName();
        $unitOfMeasure = $ingredient->getUnitOfMeasure();

        // Liaison des paramètres
        $requete->bindParam(':name', $name, PDO::PARAM_STR);
        $requete->bindParam(':unitOfMeasure', $unitOfMeasure, PDO::PARAM_STR);

        $success = $requete->execute();
        if ($success) {
            $ingredient->setId((int)$connexion->lastInsertId()); // Récupère l'ID de l'ingrédient inséré
        }

        return $success;
    }

    /**
     * Met à jour un ingrédient dans la base de données
     * @param Ingredient $ingredient L'ingrédient à mettre à jour
     * @return bool Vrai si la mise à jour a réussi, faux sinon
     */
    static public function update(object $ingredient): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $requete = $connexion->prepare(
            "UPDATE Ingredient 
             SET name = :name, unitOfMeasure = :unitOfMeasure
             WHERE id = :id"
        );

        // Récupération des valeurs de l'ingrédient
        $id = $ingredient->getId();
        $name = $ingredient->getName();
        $unitOfMeasure = $ingredient->getUnitOfMeasure();

        // Liaison des paramètres
        $requete->bindParam(':id', $id, PDO::PARAM_INT);
        $requete->bindParam(':name', $name, PDO::PARAM_STR);
        $requete->bindParam(':unitOfMeasure', $unitOfMeasure, PDO::PARAM_STR);

        return $requete->execute();
    }

    /**
     * Supprime un ingrédient de la base de données
     * @param Ingredient $ingredient L'ingrédient à supprimer
     * @return bool Vrai si la suppression a réussi, faux sinon
     */
    static public function delete(object $ingredient): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $requete = $connexion->prepare("DELETE FROM Ingredient WHERE id = :id");

        // Récupération de l'ID de l'ingrédient à supprimer
        $id = $ingredient->getId();

        // Liaison du paramètre
        $requete->bindParam(':id', $id, PDO::PARAM_INT);

        return $requete->execute();
    }

    // Ces méthodes ne sont pas implémentées pour ce DAO
    static public function findByEmail(string $email): ?object {
        return null; // Retourne null si aucun utilisateur trouvé
    }

    static public function existsByEmail(string $email): bool {
        return false;
    }
}
?>
