<?php
/**
 * DAO pour la classe Role
 */


include_once(__DIR__ . "/../role.class.php");
include_once(__DIR__ . "/DAO.interface.php");

class RoleDAO implements DAO {

    /**
     * Recherche un rôle par son code
     * 
     * @param int $roleCode Le code du rôle à chercher
     * @return Role|null Le rôle trouvé ou null si non trouvé
     */
    static public function findById(int $roleCode): ?object {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $role = null;
        $requete = $connexion->prepare("SELECT * FROM Role WHERE roleCode = :roleCode");
        $requete->bindParam(':roleCode', $roleCode, PDO::PARAM_INT);
        $requete->execute();

        if ($requete->rowCount() != 0) {
            $enr = $requete->fetch();
            $role = new Role(
                $enr['roleCode'], 
                $enr['roleName']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $role;
    }

    /**
     * Retourne une liste de tous les rôles
     * 
     * @return array Liste des rôles
     */
    static public function findAll(): array {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $roles = [];
        $requete = $connexion->prepare("SELECT * FROM Role");
        $requete->execute();

        foreach ($requete as $enr) {
            $roles[] = new Role(
                $enr['roleCode'], 
                $enr['roleName']
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $roles;
    }

    /**
     * Recherche un rôle par une description
     * 
     * @param string $filter Le filtre à appliquer (par exemple une clause WHERE)
     * @return array Une liste de rôles correspondant au filtre
     */
    static public function findByDescription(string $filter): array {
        // Cette méthode peut être adaptée à des recherches plus spécifiques en fonction des besoins
        return []; // Aucun filtre spécifique pour les rôles
    }

    /**
     * Recherche un rôle par son adresse email (non applicable pour Role)
     * 
     * @param string $email L'email à rechercher
     * @return object|null Retourne null car cette fonctionnalité n'est pas applicable
     */
    static public function findByEmail(string $email): ?object {
        return null; // Non applicable pour les rôles
    }

    /**
     * Vérifie si un rôle existe à partir d'un email (non applicable pour Role)
     * 
     * @param string $email L'email à vérifier
     * @return bool Retourne false car cette fonctionnalité n'est pas applicable
     */
    static public function existsByEmail(string $email): bool {
        return false; // Non applicable pour les rôles
    }

    /**
     * Insère un rôle dans la base de données
     * 
     * @param Role $role L'objet à insérer
     * @return bool Retourne true si l'insertion a réussi, false sinon
     */
    static public function save(object $role): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $requete = $connexion->prepare("INSERT INTO Role (roleName) VALUES (:roleName)");

        // Liaison des paramètres
        $roleName = $role->getRoleName();
        $requete->bindParam(':roleName', $roleName, PDO::PARAM_STR);

        $success = $requete->execute();
        if ($success) {
            // Récupère l'ID du rôle après l'insertion
            $role->setRoleCode((int) $connexion->lastInsertId());
        }

        ConnexionBD::close();
        return $success;
    }

    /**
     * Met à jour un rôle dans la base de données
     * 
     * @param Role $role L'objet à modifier
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function update(object $role): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $requete = $connexion->prepare("UPDATE Role SET roleName = :roleName WHERE roleCode = :roleCode");

        // Liaison des paramètres
        $roleCode = $role->getRoleCode();
        $roleName = $role->getRoleName();
        $requete->bindParam(':roleCode', $roleCode, PDO::PARAM_INT);
        $requete->bindParam(':roleName', $roleName, PDO::PARAM_STR);

        $success = $requete->execute();
        ConnexionBD::close();
        return $success;
    }

    /**
     * Supprime un rôle de la base de données
     * 
     * @param Role $role L'objet à supprimer
     * @return bool Retourne true si l'opération est réussie, false sinon
     */
    static public function delete(object $role): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $requete = $connexion->prepare("DELETE FROM Role WHERE roleCode = :roleCode");

        // Liaison du paramètre pour le code du rôle
        $roleCode = $role->getRoleCode();
        $requete->bindParam(':roleCode', $roleCode, PDO::PARAM_INT);

        $success = $requete->execute();
        ConnexionBD::close();
        return $success;
    }
}
?>
