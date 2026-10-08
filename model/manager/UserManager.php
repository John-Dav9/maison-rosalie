<?php

namespace model\manager;

use PDO;
use Throwable;
use model\mapping\UserMapping;
use model\ManagerInterface;
use model\UserInterface;

class UserManager implements ManagerInterface, UserInterface
{
    // Cette connexion PDO est fournie par le code qui crée le manager.
    protected PDO $connect;

    // Le manager conserve la connexion pour ses requêtes SQL.
    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }

    /** Connecte un utilisateur à partir de son email et de son mot de passe. */
    public function connect(array $tab): bool
    {
        // Les deux valeurs sont nécessaires pour tenter une connexion.
        if (!isset($tab['user_mail'], $tab['user_pwd'])) {
            return false;
        }

        try {
            // Le mapping valide l'adresse email avant son utilisation.
            $credentials = new UserMapping([
                'user_mail' => $tab['user_mail'],
                'user_pwd' => $tab['user_pwd'],
            ]);

            // Les alias donnent aux colonnes SQL les noms attendus par UserMapping.
            $sql = "SELECT
                        `Id` AS `user_id`,
                        `Name` AS `user_real_name`,
                        `Email` AS `user_mail`,
                        `Pwd` AS `user_pwd`,
                        `Role` AS `user_role_id`,
                        `Creat_At` AS `user_date_inscription`
                    FROM `Users`
                    WHERE `Email` = :email
                    LIMIT 1";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute(['email' => $credentials->getUserMail()]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            // On compare le mot de passe saisi au hash stocké, sans récupérer le mot de passe en clair.
            if ($result === false || !password_verify($credentials->getUserPwd(), $result['user_pwd'])) {
                return false;
            }

            // Démarre la session si le contrôleur ne l'a pas déjà fait.
            if (session_status() !== PHP_SESSION_ACTIVE && !session_start()) {
                return false;
            }

            // Renouvelle l'identifiant de session après une authentification réussie.
            session_regenerate_id(true);
            unset($result['user_pwd']);
            $_SESSION = $result;

            return true;
        } catch (Throwable $e) {
            error_log('Échec de connexion utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    /** Détruit la session de l'utilisateur courant. */
    public function disconnect(): bool
    {
        // Il n'y a rien à détruire si aucune session n'est active.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return false;
        }

        // Vide les données de session avant de supprimer la session.
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }

        return session_destroy();
    }

    /** Récupère un utilisateur avec son adresse email, sans charger son hash de mot de passe. */
    public function getUserByEmail(string $email): ?UserMapping
    {
        // Le hash du mot de passe n'est pas sélectionné pour cette recherche.
        $sql = "SELECT
                    `Id` AS `user_id`,
                    `Name` AS `user_real_name`,
                    `Email` AS `user_mail`,
                    `Role` AS `user_role_id`,
                    `Creat_At` AS `user_date_inscription`
                FROM `Users`
                WHERE `Email` = :email
                LIMIT 1";

        try {
            $stmt = $this->connect->prepare($sql);
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $result === false ? null : new UserMapping($result);
        } catch (Throwable $e) {
            error_log('Échec de récupération utilisateur : ' . $e->getMessage());
            return null;
        }
    }

    /** Retourne tous les utilisateurs sans leurs mots de passe. */
    public function getAllUsers(): array
    {
        // Cette liste ne récupère pas les mots de passe.
        $sql = "SELECT
                    `Id` AS `user_id`,
                    `Name` AS `user_real_name`,
                    `Email` AS `user_mail`,
                    `Role` AS `user_role_id`,
                    `Creat_At` AS `user_date_inscription`
                FROM `Users`
                ORDER BY `Name`";

        try {
            $stmt = $this->connect->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $users = [];
            // Chaque ligne SQL devient un objet utilisable par le reste de l'application.
            foreach ($results as $result) {
                $users[] = new UserMapping($result);
            }

            return $users;
        } catch (Throwable $e) {
            error_log('Échec de récupération des utilisateurs : ' . $e->getMessage());
            return [];
        }
    }

    /** Inscrit un utilisateur normal (rôle 0) dans la table Users. */
    public function register(array $data): UserMapping|bool
    {
        // Vérifie que les champs attendus par le formulaire sont présents.
        if (!isset($data['user_real_name'], $data['user_mail'], $data['user_pwd'], $data['user_pwd_confirm'])) {
            return false;
        }

        // Évite de créer le compte si la confirmation diffère du mot de passe.
        if ($data['user_pwd'] !== $data['user_pwd_confirm']) {
            throw new \InvalidArgumentException('Les mots de passe ne correspondent pas.');
        }

        // Le mapping valide et prépare les données avant l'insertion.
        $user = new UserMapping([
            'user_real_name' => $data['user_real_name'],
            'user_mail' => $data['user_mail'],
            'user_pwd' => $data['user_pwd'],
        ]);

        // Un email déjà utilisé ne peut pas créer un second compte.
        if ($this->userExists($user->getUserMail())) {
            throw new \InvalidArgumentException('Cette adresse email est déjà utilisée.');
        }

        // Seul le hash du mot de passe sera enregistré en base.
        $passwordHash = password_hash($user->getUserPwd(), PASSWORD_DEFAULT);
        if ($passwordHash === false) {
            return false;
        }

        // Le rôle 0 est attribué par défaut et la date est créée par MySQL.
        $sql = "INSERT INTO `Users` (`Name`, `Email`, `Pwd`, `Role`, `Creat_At`)
                VALUES (:name, :email, :pwd, 0, CURRENT_TIMESTAMP)";

        try {
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([
                'name' => $user->getUserRealName(),
                'email' => $user->getUserMail(),
                'pwd' => $passwordHash,
            ]);

            // Récupère l'ID généré automatiquement pour compléter l'objet retourné.
            $user->setUserId((int) $this->connect->lastInsertId());
            $user->setUserPwd($passwordHash);
            $user->setUserRoleId(0);
            $user->setUserDateInscription(date('Y-m-d H:i:s'));

            return $user;
        } catch (Throwable $e) {
            error_log('Échec d’inscription utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    /** Indique si une adresse email est déjà enregistrée. */
    public function userExists(string $email): bool
    {
        // Vérifie si l'adresse email existe déjà sans charger toute la ligne.
        $sql = "SELECT 1 FROM `Users` WHERE `Email` = :email LIMIT 1";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute(['email' => $email]);

        return $stmt->fetchColumn() !== false;
    }
}
