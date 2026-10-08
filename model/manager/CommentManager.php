<?php

namespace model\manager;

use PDO;
use Throwable;
use model\ManagerInterface;
use model\mapping\CommentsMapping;

/** Gère la création et la lecture des commentaires associés aux recettes. */
class CommentManager implements ManagerInterface
{
    protected PDO $connect;

    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }

    /**
     * Ajoute un commentaire. Le nom affiché de l'auteur est lu depuis Users,
     * ce qui évite qu'un formulaire puisse se faire passer pour une autre personne.
     */
    public function addComment(int $recipeId, int $userId, string $message, ?string $subject = null): CommentsMapping|false
    {
        $message = trim($message);
        $subject = $subject === null || trim($subject) === '' ? null : trim($subject);

        if ($recipeId <= 0 || $userId <= 0 || $message === '' || strlen($message) > 300
            || ($subject !== null && strlen($subject) > 200)) {
            return false;
        }

        try {
            // Récupère le nom de l'auteur depuis son identifiant connecté.
            $authorQuery = $this->connect->prepare("SELECT `Name` FROM `Users` WHERE `Id` = :id LIMIT 1");
            $authorQuery->execute(['id' => $userId]);
            $author = $authorQuery->fetchColumn();
            $authorQuery->closeCursor();

            if ($author === false || strlen((string) $author) > 100) {
                return false;
            }

            // L'ID du commentaire et la date sont générés automatiquement par la base.
            $sql = "INSERT INTO `Comments` (`Author`, `Subject`, `Message`, `Created_at`, `Recipe_Id`, `Users_Id`)
                    VALUES (:author, :subject, :message, CURRENT_TIMESTAMP, :recipe_id, :user_id)";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([
                'author' => $author,
                'subject' => $subject,
                'message' => $message,
                'recipe_id' => $recipeId,
                'user_id' => $userId,
            ]);

            // Retourne un mapping complet avec l'ID créé par MySQL.
            return new CommentsMapping([
                'Id' => (int) $this->connect->lastInsertId(),
                'Author' => $author,
                'Subject' => $subject,
                'Message' => $message,
                'Created_at' => date('Y-m-d H:i:s'),
                'Recipe_Id' => $recipeId,
                'Users_Id' => $userId,
            ]);
        } catch (Throwable $e) {
            error_log('Échec d’ajout du commentaire : ' . $e->getMessage());
            return false;
        }
    }

    /** Retourne les commentaires d'une recette, du plus récent au plus ancien. */
    public function getCommentsByRecipeId(int $recipeId): array
    {
        if ($recipeId <= 0) {
            return [];
        }

        $sql = "SELECT `Id`, `Author`, `Subject`, `Message`, `Created_at`, `Recipe_Id`, `Users_Id`
                FROM `Comments`
                WHERE `Recipe_Id` = :recipe_id
                ORDER BY `Created_at` DESC, `Id` DESC";

        try {
            $stmt = $this->connect->prepare($sql);
            $stmt->execute(['recipe_id' => $recipeId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map(static fn(array $row): CommentsMapping => new CommentsMapping($row), $rows);
        } catch (Throwable $e) {
            error_log('Échec de récupération des commentaires : ' . $e->getMessage());
            return [];
        }
    }
}
