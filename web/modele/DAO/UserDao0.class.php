<?php
/**
 * Description : DAO pour la classe User et les tables User et Role
 */

include_once(__DIR__ . "/DAO.interface.php");
include_once(__DIR__ . "/../user.class.php");
include_once(__DIR__ . "/../role.class.php");

class UserDAO implements DAO {

    /**
     * Recherche un utilisateur par ID
     * @param int $id
     * @return User|null
     */
    static public function findById(int $id): ?User {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $user = null;
        $requete = $connexion->prepare(
            "SELECT u.*, r.nomRole 
            FROM [User] u 
            JOIN [Role] r ON u.roleID = r.codeRole 
            WHERE u.id = :id"
        );
        $requete->bindParam(':id', $id, PDO::PARAM_INT);
        $requete->execute();

        if ($requete->rowCount() != 0) {
            $enr = $requete->fetch();
            $user = new User(
                $enr['id'],
                $enr['nom'],
                $enr['email'],
                $enr['password'],
                new Role($enr['role'], $enr['nomRole'])
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $user;
    }

    /**
     * Retourne tous les utilisateurs
     * @return array
     */
    static public function findAll(): array {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        $users = [];
        $requete = $connexion->prepare(
            "SELECT u.*, r.nomRole 
             FROM [User] u 
             JOIN [Role] r ON u.id = r.codeRole"
        );
        $requete->execute();

        foreach ($requete as $enr) {
            $users[] = new User(
                $enr['id'],
                $enr['nom'],
                $enr['email'],
                $enr['password'],
                new Role($enr['role'], $enr['nomRole'])
            );
        }

        $requete->closeCursor();
        ConnexionBD::close();

        return $users;
    }

    /**
     * Insère un nouvel utilisateur dans la base de données
     * @param User $user
     * @return bool
     */
    static public function save(object $user): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        // Stockage dans des variables intermédiaires
        $nom = $user->getNom();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $roleId = $user->getRole()->getCodeRole()??3; // Par défaut, "Client"
    
        if (strlen($password) < 60) { // Les mots de passe hachés avec bcrypt ont une longueur de 60 caractères
            $password = password_hash($password, PASSWORD_BCRYPT); // Hachage si nécessaire
        }

        $requete = $connexion->prepare(
            "INSERT INTO User (nom, email, password, role) 
             VALUES (:nom, :email, :password, :roleId)"
        );

        // Liaison des paramètres
        $requete->bindParam(':nom', $nom, PDO::PARAM_STR);
        $requete->bindParam(':email', $email, PDO::PARAM_STR);
        $requete->bindParam(':password', $password, PDO::PARAM_STR);
        $requete->bindParam(':roleId', $roleId, PDO::PARAM_INT);

        $success = $requete->execute();
        if ($success) {
            $user->setId((int)$connexion->lastInsertId());
        }

        return $success;
    }

    /**
     * Met à jour un utilisateur existant
     * @param User $user
     * @return bool
     */
    static public function update(object $user): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }
    
        // Stockage dans des variables intermédiaires
        $id = $user->getId();
        $nom = $user->getNom();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $roleId = $user->getRole()->getCodeRole();

        // Vérifier si le mot de passe est déjà haché
        if (strlen($password) < 60) { // Les mots de passe hachés avec bcrypt ont une longueur de 60 caractères
            $password = password_hash($password, PASSWORD_BCRYPT); // Hachage si nécessaire
        }
    
        $requete = $connexion->prepare(
            "UPDATE User 
             SET nom = :nom, email = :email, 
                 password = :password, role = :roleId 
             WHERE id = :id"
        );
    
        // Liaison des paramètres
        $requete->bindParam(':id', $id, PDO::PARAM_INT);
        $requete->bindParam(':nom', $nom, PDO::PARAM_STR);
        $requete->bindParam(':email', $email, PDO::PARAM_STR);
        $requete->bindParam(':password', $password, PDO::PARAM_STR);
        $requete->bindParam(':roleId', $roleId, PDO::PARAM_INT);
    
        return $requete->execute();
    }
    

    /**
     * Supprime un utilisateur de la base de données
     * @param User $user
     * @return bool
     */
    static public function delete(object $user): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }

        // Stockage dans une variable intermédiaire
        $id = $user->getId();

        $requete = $connexion->prepare("DELETE FROM User WHERE id = :id");

        // Liaison du paramètre
        $requete->bindParam(':id', $id, PDO::PARAM_INT);

        return $requete->execute();
    }

    static public function findByEmail(string $email): ?User {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }
    
        $requete = $connexion->prepare(
            "SELECT u.*, r.nomRole 
             FROM [User] u 
             JOIN [Role] r ON u.role = r.codeRole 
             WHERE u.email = :email"
        );
        $requete->bindParam(':email', $email, PDO::PARAM_STR);
        $requete->execute();
    
        if ($requete->rowCount() != 0) {
            $enr = $requete->fetch();
            return new User(
                $enr['id'],
                $enr['nom'],
                $enr['email'],
                $enr['password'],
                new Role($enr['role'], $enr['nomRole'])
            );
        }
    
        return null; // Retourne null si aucun utilisateur trouvé
    }
    

    static public function existsByEmail(string $email): bool {
        try {
            $connexion = ConnexionBD::getInstance();
        } catch (Exception $e) {
            throw new Exception("Impossible d'obtenir la connexion à la BD");
        }
    
        $requete = $connexion->prepare(
            "SELECT COUNT(*) as count FROM User WHERE email = :email"
        );
        $requete->bindParam(':email', $email, PDO::PARAM_STR);
        $requete->execute();
    
        $result = $requete->fetch();
        return $result['count'] > 0;
    }
    
    public static function findByDescription(string $filter): array{
        $tableau = [];
        return $tableau;  
    }
}


?>




