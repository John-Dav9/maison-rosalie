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

    /** Enregistre ou remplace la note d'un utilisateur pour une recette. */
    public function addNote(int $note, int $userId, int $recipeId): NoteMapping|false
    {
        return $this->saveNote($note, $userId, $recipeId);
    }

    /** Retourne uniquement la note actuelle de chaque utilisateur pour une recette. */
    public function getNotesByRecipeId(int $recipeId): array
    {
        if ($recipeId <= 0) {
            return [];
        }

        // Ignore les anciennes lignes dupliquées qui peuvent exister avant le remplacement des notes.
        $sql = "SELECT `current_note`.`Id`, `current_note`.`Note`, `current_note`.`Created_at`, `current_note`.`Users_Id`, `current_note`.`Recipe_Id`
                FROM `Note` AS `current_note`
                INNER JOIN (
                    SELECT MAX(`Id`) AS `latest_id`
                    FROM `Note`
                    WHERE `Recipe_Id` = :recipe_id
                    GROUP BY `Recipe_Id`, `Users_Id`
                ) AS `latest_notes` ON `latest_notes`.`latest_id` = `current_note`.`Id`
                ORDER BY `current_note`.`Created_at` DESC, `current_note`.`Id` DESC";

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

    /** Calcule la moyenne des notes actuelles, en ne gardant qu'un vote par utilisateur. */
    public function getAverageForRecipe(int $recipeId): ?float
    {
        if ($recipeId <= 0) {
            return null;
        }

        $summary = $this->getRatingSummariesByRecipeIds([$recipeId]);
        return $summary[$recipeId]['average'] ?? null;
    }

    /**
     * Enregistre la note actuelle de l'utilisateur pour une recette.
     * Une nouvelle ligne n'est créée que si aucune note n'existe encore.
     */
    public function saveNote(int $note, int $userId, int $recipeId): NoteMapping|false
    {
        if ($note < 1 || $note > 5 || $userId <= 0 || $recipeId <= 0) {
            return false;
        }

        try {
            $this->connect->beginTransaction();

            // Le verrouillage de la recette sérialise les premiers votes simultanés sur cette recette.
            $recipeLock = $this->connect->prepare("SELECT `Id` FROM `Recipe` WHERE `Id` = :recipe_id FOR UPDATE");
            $recipeLock->execute(['recipe_id' => $recipeId]);
            if ($recipeLock->fetchColumn() === false) {
                $recipeLock->closeCursor();
                $this->connect->rollBack();
                return false;
            }
            $recipeLock->closeCursor();

            // Les anciennes versions pouvaient créer plusieurs lignes; on réutilise la plus récente.
            $existingQuery = $this->connect->prepare(
                "SELECT `Id` FROM `Note` WHERE `Recipe_Id` = :recipe_id AND `Users_Id` = :user_id ORDER BY `Id` DESC LIMIT 1 FOR UPDATE"
            );
            $existingQuery->execute(['recipe_id' => $recipeId, 'user_id' => $userId]);
            $existingId = $existingQuery->fetchColumn();
            $existingQuery->closeCursor();

            if ($existingId !== false) {
                $noteId = (int) $existingId;
                $stmt = $this->connect->prepare(
                    "UPDATE `Note` SET `Note` = :note, `Created_at` = CURRENT_TIMESTAMP WHERE `Id` = :id AND `Recipe_Id` = :recipe_id AND `Users_Id` = :user_id"
                );
                $stmt->execute([
                    'note' => $note,
                    'id' => $noteId,
                    'recipe_id' => $recipeId,
                    'user_id' => $userId,
                ]);
            } else {
                $stmt = $this->connect->prepare(
                    "INSERT INTO `Note` (`Note`, `Created_at`, `Users_Id`, `Recipe_Id`) VALUES (:note, CURRENT_TIMESTAMP, :user_id, :recipe_id)"
                );
                $stmt->execute(['note' => $note, 'user_id' => $userId, 'recipe_id' => $recipeId]);
                $noteId = (int) $this->connect->lastInsertId();
            }

            $this->connect->commit();
            return new NoteMapping([
                'Id' => $noteId,
                'Note' => $note,
                'Created_at' => date('Y-m-d H:i:s'),
                'Users_Id' => $userId,
                'Recipe_Id' => $recipeId,
            ]);
        } catch (Throwable $e) {
            if ($this->connect->inTransaction()) {
                $this->connect->rollBack();
            }
            error_log('Échec d’enregistrement de la note : ' . $e->getMessage());
            return false;
        }
    }

    /** Retourne la dernière note d'un utilisateur pour une recette, s'il en a déjà donné une. */
    public function getNoteForUserAndRecipe(int $recipeId, int $userId): ?NoteMapping
    {
        if ($recipeId <= 0 || $userId <= 0) {
            return null;
        }

        try {
            $stmt = $this->connect->prepare(
                "SELECT `Id`, `Note`, `Created_at`, `Users_Id`, `Recipe_Id` FROM `Note` WHERE `Recipe_Id` = :recipe_id AND `Users_Id` = :user_id ORDER BY `Id` DESC LIMIT 1"
            );
            $stmt->execute(['recipe_id' => $recipeId, 'user_id' => $userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $row === false ? null : new NoteMapping($row);
        } catch (Throwable $e) {
            error_log('Échec de récupération de la note utilisateur : ' . $e->getMessage());
            return null;
        }
    }

    /** Retourne le nombre et la moyenne des notes pour les recettes fournies. */
    public function getRatingSummariesByRecipeIds(array $recipeIds): array
    {
        $recipeIds = array_values(array_unique(array_filter(
            array_map('intval', $recipeIds),
            static fn(int $id): bool => $id > 0
        )));
        if ($recipeIds === []) {
            return [];
        }

        $placeholders = [];
        $parameters = [];
        foreach ($recipeIds as $index => $recipeId) {
            $placeholder = ':recipe_' . $index;
            $placeholders[] = $placeholder;
            $parameters['recipe_' . $index] = $recipeId;
        }

        // Une ancienne version pouvant avoir empilé des votes, on ne compte que la dernière note de chaque utilisateur.
        $sql = 'SELECT `current_note`.`Recipe_Id` AS recipe_id, COUNT(*) AS rating_count, AVG(`current_note`.`Note`) AS rating_average '
            . 'FROM `Note` AS `current_note` '
            . 'INNER JOIN (SELECT `Recipe_Id`, `Users_Id`, MAX(`Id`) AS `latest_id` '
            . 'FROM `Note` WHERE `Recipe_Id` IN (' . implode(', ', $placeholders) . ') '
            . 'GROUP BY `Recipe_Id`, `Users_Id`) AS `latest_notes` '
            . 'ON `latest_notes`.`latest_id` = `current_note`.`Id` '
            . 'GROUP BY `current_note`.`Recipe_Id`';

        try {
            $stmt = $this->connect->prepare($sql);
            $stmt->execute($parameters);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            $summaries = [];
            foreach ($rows as $row) {
                $recipeId = (int) $row['recipe_id'];
                $summaries[$recipeId] = [
                    'count' => (int) $row['rating_count'],
                    'average' => $row['rating_average'] === null ? null : (float) $row['rating_average'],
                ];
            }
            return $summaries;
        } catch (Throwable $e) {
            error_log('Échec de récupération des résumés de notes : ' . $e->getMessage());
            return [];
        }
    }
}
