<?php

namespace model\manager;

use PDO;
use Throwable;
use model\ManagerInterface;
use model\mapping\NoteMapping;

/** Gère l'ajout et la consultation des notes laissées sur les recettes. */
class NoteManager implements ManagerInterface
{
    protected PDO $connect;

    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }

    /**
     * Enregistre une note associée à une recette et à un utilisateur.
     * Le schéma SQL définit Note comme un TINYINT signé; aucune échelle métier
     * (par exemple 1 à 5) n'est imposée ici, car elle n'est pas indiquée dans la base.
     */
    public function addNote(int $note, int $userId, int $recipeId): NoteMapping|false
    {
        if ($userId <= 0 || $recipeId <= 0 || $note < -128 || $note > 127) {
            return false;
        }

        try {
            // La date et l'identifiant de la note sont créés automatiquement.
            $sql = "INSERT INTO `Note` (`Note`, `Created_at`, `Users_Id`, `Recipe_Id`)
                    VALUES (:note, CURRENT_TIMESTAMP, :user_id, :recipe_id)";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([
                'note' => $note,
                'user_id' => $userId,
                'recipe_id' => $recipeId,
            ]);

            return new NoteMapping([
                'Id' => (int) $this->connect->lastInsertId(),
                'Note' => $note,
                'Created_at' => date('Y-m-d H:i:s'),
                'Users_Id' => $userId,
                'Recipe_Id' => $recipeId,
            ]);
        } catch (Throwable $e) {
            error_log('Échec d’ajout de la note : ' . $e->getMessage());
            return false;
        }
    }

    /** Retourne toutes les notes associées à une recette. */
    public function getNotesByRecipeId(int $recipeId): array
    {
        if ($recipeId <= 0) {
            return [];
        }

        $sql = "SELECT `Id`, `Note`, `Created_at`, `Users_Id`, `Recipe_Id`
                FROM `Note`
                WHERE `Recipe_Id` = :recipe_id
                ORDER BY `Created_at` DESC, `Id` DESC";

        try {
            $stmt = $this->connect->prepare($sql);
            $stmt->execute(['recipe_id' => $recipeId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map(static fn(array $row): NoteMapping => new NoteMapping($row), $rows);
        } catch (Throwable $e) {
            error_log('Échec de récupération des notes : ' . $e->getMessage());
            return [];
        }
    }

    /** Calcule la moyenne des notes; retourne null si la recette n'a encore aucune note. */
    public function getAverageForRecipe(int $recipeId): ?float
    {
        if ($recipeId <= 0) {
            return null;
        }

        try {
            $stmt = $this->connect->prepare("SELECT AVG(`Note`) FROM `Note` WHERE `Recipe_Id` = :recipe_id");
            $stmt->execute(['recipe_id' => $recipeId]);
            $average = $stmt->fetchColumn();
            $stmt->closeCursor();

            return $average === false || $average === null ? null : (float) $average;
        } catch (Throwable $e) {
            error_log('Échec de calcul de la moyenne des notes : ' . $e->getMessage());
            return null;
        }
    }
}
