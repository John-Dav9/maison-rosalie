<?php

namespace model\manager;

use InvalidArgumentException;
use PDO;
use RuntimeException;
use Throwable;
use model\ManagerInterface;
use model\mapping\CategoryMapping;
use model\mapping\IngredientMapping;
use model\mapping\RecetteMapping;
use model\mapping\StepsMapping;

/**
 * Gère l'enregistrement et la lecture des recettes et de leurs éléments liés.
 * Une création complète est faite dans une transaction afin d'éviter une recette
 * partiellement enregistrée si une étape ou une association échoue.
 */
class RecetteManager implements ManagerInterface
{
    protected PDO $connect;

    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }

    /**
     * Crée une recette, ses étapes, ses catégories et ses ingrédients.
     *
     * Format attendu pour $data :
     * - recette_title, recette_description, recette_image,
     *   recette_duration_prep, recette_duration_cook, recette_difficulty
     * - categories : ID existants, ou tableaux ['title' => ..., 'description' => ...]
     * - ingredients : tableaux ['id' => ID, 'quantite' => ...] ou
     *   ['name' => ..., 'image' => ..., 'quantite' => ...] pour un nouvel ingrédient
     * - steps : tableaux ['title' => ..., 'description' => ..., 'image' => ...]
     *
     * L'identifiant du créateur est fourni séparément pour éviter de le prendre
     * depuis des données de formulaire modifiables par le visiteur.
     */
    public function createRecipe(array $data, int $userId): RecetteMapping|false
    {
        if ($userId <= 0 || !$this->hasRequiredRecipeFields($data)) {
            return false;
        }

        if (!is_array($data['categories'] ?? null)
            || !is_array($data['ingredients'] ?? null)
            || !is_array($data['steps'] ?? null)
            || count($data['ingredients']) === 0
            || count($data['steps']) === 0) {
            return false;
        }

        try {
            // Le mapping valide le titre et la description avant l'écriture en base.
            $recipe = new RecetteMapping([
                'recette_title' => $data['recette_title'],
                'recette_description' => $data['recette_description'],
                'recette_image' => $data['recette_image'],
                'recette_duration_prep' => $data['recette_duration_prep'],
                'recette_duration_cook' => $data['recette_duration_cook'],
                'recette_difficulty' => $data['recette_difficulty'],
                'recette_date_create' => date('Y-m-d H:i:s'),
                'recette_user_id' => $userId,
            ]);

            $this->connect->beginTransaction();

            // Insère d'abord la recette pour obtenir son ID auto-incrémenté.
            $sqlRecipe = "INSERT INTO `Recipe`
                (`Title`, `Description`, `Image`, `duration_prep`, `duration_cook`, `difficulty`, `Created_at`, `Users_Id`)
                VALUES (:title, :description, :image, :duration_prep, :duration_cook, :difficulty, :created_at, :user_id)";
            $stmtRecipe = $this->connect->prepare($sqlRecipe);
            $stmtRecipe->execute([
                'title' => $recipe->getRecetteTitle(),
                'description' => $recipe->getRecetteDescription(),
                'image' => $recipe->getRecetteImage(),
                'duration_prep' => $recipe->getRecetteDurationPrep(),
                'duration_cook' => $recipe->getRecetteDurationCook(),
                'difficulty' => $recipe->getRecetteDifficulty(),
                'created_at' => $recipe->getRecetteDateCreate(),
                'user_id' => $userId,
            ]);

            $recipeId = (int) $this->connect->lastInsertId();
            if ($recipeId <= 0) {
                throw new RuntimeException("Impossible de récupérer l'identifiant de la recette créée");
            }
            $recipe->setRecetteId($recipeId);

            // Réutilise les catégories sélectionnées ou crée celles qui manquent.
            $categoryIds = [];
            foreach ($data['categories'] as $category) {
                $categoryIds[] = $this->resolveCategoryId($category);
            }
            foreach (array_unique($categoryIds) as $categoryId) {
                $stmtCategoryLink = $this->connect->prepare(
                    "INSERT INTO `Category_has_Recipe` (`Category_Id`, `Recipe_Id`) VALUES (?, ?)"
                );
                $stmtCategoryLink->execute([$categoryId, $recipeId]);
            }

            // Réutilise les ingrédients sélectionnés ou crée les nouveaux.
            foreach ($data['ingredients'] as $ingredient) {
                if (!is_array($ingredient)) {
                    throw new InvalidArgumentException("Chaque ingrédient doit être décrit dans un tableau");
                }

                $ingredientId = $this->resolveIngredientId($ingredient);
                $quantity = $ingredient['quantite'] ?? $ingredient['quantity'] ?? null;
                if ($quantity !== null && (!is_string($quantity) || strlen($quantity) > 45)) {
                    throw new InvalidArgumentException("La quantité ne doit pas dépasser 45 caractères");
                }

                $stmtIngredientLink = $this->connect->prepare(
                    "INSERT INTO `Ingredient_has_Recipe` (`Ingredient_Id`, `Recipe_Id`, `quantite`) VALUES (?, ?, ?)"
                );
                $stmtIngredientLink->execute([$ingredientId, $recipeId, $quantity]);
            }

            // Les deux colonnes de recette présentes dans Steps reçoivent le même ID.
            $stmtStep = $this->connect->prepare(
                "INSERT INTO `Steps` (`Recette_id`, `nb_step`, `Title`, `Description`, `image`, `Recipe_Id`)
                 VALUES (:recette_id, :step_number, :title, :description, :image, :recipe_id)"
            );
            foreach (array_values($data['steps']) as $index => $step) {
                if (!is_array($step)) {
                    throw new InvalidArgumentException("Chaque étape doit être décrite dans un tableau");
                }

                $title = trim((string) ($step['title'] ?? ''));
                $description = trim((string) ($step['description'] ?? ''));
                $image = trim((string) ($step['image'] ?? ''));
                $stepNumber = filter_var($step['nb_step'] ?? ($index + 1), FILTER_VALIDATE_INT);

                if ($title === '' || strlen($title) > 100
                    || $description === '' || strlen($description) > 200
                    || strlen($image) > 300) {
                    throw new InvalidArgumentException("Le titre, la description ou l'image d'une étape n'est pas valide");
                }
                if ($stepNumber === false || $stepNumber <= 0) {
                    $stepNumber = $index + 1;
                }

                $stmtStep->execute([
                    'recette_id' => $recipeId,
                    'step_number' => $stepNumber,
                    'title' => $title,
                    'description' => $description,
                    // La colonne image n'accepte pas NULL; une chaîne vide signifie aucune image.
                    'image' => $image,
                    'recipe_id' => $recipeId,
                ]);
            }

            $this->connect->commit();
            return $recipe;
        } catch (Throwable $e) {
            if ($this->connect->inTransaction()) {
                $this->connect->rollBack();
            }
            error_log('Échec de création de recette : ' . $e->getMessage());
            return false;
        }
    }

    /** Retourne toutes les recettes sous forme d'objets RecetteMapping. */
    public function getAllRecettes(): array
    {
        $sql = $this->recipeSelectSql() . " ORDER BY `Created_at` DESC";

        try {
            $stmt = $this->connect->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map(static fn(array $row): RecetteMapping => new RecetteMapping($row), $rows);
        } catch (Throwable $e) {
            error_log('Échec de récupération des recettes : ' . $e->getMessage());
            return [];
        }
    }

    /** Récupère une recette à partir de son identifiant. */
    public function getRecetteById(int $recipeId): ?RecetteMapping
    {
        if ($recipeId <= 0) {
            return null;
        }

        try {
            $stmt = $this->connect->prepare($this->recipeSelectSql() . " WHERE `Id` = :id LIMIT 1");
            $stmt->execute(['id' => $recipeId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $row === false ? null : new RecetteMapping($row);
        } catch (Throwable $e) {
            error_log('Échec de récupération de la recette : ' . $e->getMessage());
            return null;
        }
    }

    /** Donne au formulaire la liste des catégories déjà enregistrées. */
    public function getAllCategories(): array
    {
        try {
            $stmt = $this->connect->query("SELECT `Id`, `Title`, `Description` FROM `Category` ORDER BY `Title`");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map(static fn(array $row): CategoryMapping => new CategoryMapping($row), $rows);
        } catch (Throwable $e) {
            error_log('Échec de récupération des catégories : ' . $e->getMessage());
            return [];
        }
    }

    /** Donne au formulaire la liste des ingrédients déjà enregistrés. */
    public function getAllIngredients(): array
    {
        try {
            $stmt = $this->connect->query("SELECT `Id`, `Name`, `image` FROM `Ingredient` ORDER BY `Name`");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map(static fn(array $row): IngredientMapping => new IngredientMapping($row), $rows);
        } catch (Throwable $e) {
            error_log('Échec de récupération des ingrédients : ' . $e->getMessage());
            return [];
        }
    }

    /** Récupère les étapes d'une recette dans leur ordre de présentation. */
    public function getStepsByRecetteId(int $recipeId): array
    {
        try {
            $stmt = $this->connect->prepare(
                "SELECT `Id`, `Recette_id`, `nb_step`, `Title`, `Description`, `image`, `Recipe_Id`
                 FROM `Steps` WHERE `Recipe_Id` = :id ORDER BY `nb_step`"
            );
            $stmt->execute(['id' => $recipeId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map(static fn(array $row): StepsMapping => new StepsMapping($row), $rows);
        } catch (Throwable $e) {
            error_log('Échec de récupération des étapes : ' . $e->getMessage());
            return [];
        }
    }

    /** Retourne les ingrédients et leur quantité pour une recette donnée. */
    public function getIngredientsByRecetteId(int $recipeId): array
    {
        $sql = "SELECT i.`Id` AS `ingredient_id`, i.`Name` AS `name`, i.`image`, ir.`quantite`
                FROM `Ingredient_has_Recipe` ir
                INNER JOIN `Ingredient` i ON i.`Id` = ir.`Ingredient_Id`
                WHERE ir.`Recipe_Id` = :id
                ORDER BY i.`Name`";

        try {
            $stmt = $this->connect->prepare($sql);
            $stmt->execute(['id' => $recipeId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $rows;
        } catch (Throwable $e) {
            error_log('Échec de récupération des ingrédients de la recette : ' . $e->getMessage());
            return [];
        }
    }

    /** Retourne les catégories associées à une recette. */
    public function getCategoriesByRecetteId(int $recipeId): array
    {
        $sql = "SELECT c.`Id`, c.`Title`, c.`Description`
                FROM `Category_has_Recipe` cr
                INNER JOIN `Category` c ON c.`Id` = cr.`Category_Id`
                WHERE cr.`Recipe_Id` = :id
                ORDER BY c.`Title`";

        try {
            $stmt = $this->connect->prepare($sql);
            $stmt->execute(['id' => $recipeId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return array_map(static fn(array $row): CategoryMapping => new CategoryMapping($row), $rows);
        } catch (Throwable $e) {
            error_log('Échec de récupération des catégories de la recette : ' . $e->getMessage());
            return [];
        }
    }

    /** Regroupe la recette et tous les éléments nécessaires à sa page de détail. */
    public function getRecetteDetails(int $recipeId): ?array
    {
        $recipe = $this->getRecetteById($recipeId);
        if ($recipe === null) {
            return null;
        }

        $recipe->setCategories($this->getCategoriesByRecetteId($recipeId));

        return [
            'recette' => $recipe,
            'etapes' => $this->getStepsByRecetteId($recipeId),
            'ingredients' => $this->getIngredientsByRecetteId($recipeId),
        ];
    }

    /**
     * Trouve une catégorie par son ID ou son titre; crée-la si le formulaire fournit un nouveau titre.
     */
    private function resolveCategoryId(mixed $category): int
    {
        $idValue = is_array($category) ? ($category['id'] ?? null) : $category;
        $id = filter_var($idValue, FILTER_VALIDATE_INT);

        if ($id !== false && $id > 0) {
            $stmt = $this->connect->prepare("SELECT `Id` FROM `Category` WHERE `Id` = ?");
            $stmt->execute([$id]);
            $foundId = $stmt->fetchColumn();
            $stmt->closeCursor();
            if ($foundId === false) {
                throw new InvalidArgumentException("La catégorie sélectionnée n'existe pas");
            }
            return (int) $foundId;
        }

        $title = is_array($category) ? ($category['title'] ?? $category['name'] ?? null) : $category;
        if (!is_string($title)) {
            throw new InvalidArgumentException("Il faut sélectionner une catégorie ou fournir son titre");
        }
        $title = trim($title);
        if ($title === '' || strlen($title) > 100) {
            throw new InvalidArgumentException("Le titre de la catégorie doit faire de 1 à 100 caractères");
        }

        // Si une catégorie de ce titre existe déjà, on la réutilise au lieu de la dupliquer.
        $find = $this->connect->prepare("SELECT `Id` FROM `Category` WHERE `Title` = ? LIMIT 1");
        $find->execute([$title]);
        $existingId = $find->fetchColumn();
        $find->closeCursor();
        if ($existingId !== false) {
            return (int) $existingId;
        }

        $description = is_array($category) ? ($category['description'] ?? null) : null;
        if ($description !== null && (!is_string($description) || strlen($description) > 300)) {
            throw new InvalidArgumentException("La description de catégorie ne doit pas dépasser 300 caractères");
        }

        $insert = $this->connect->prepare("INSERT INTO `Category` (`Title`, `Description`) VALUES (?, ?)");
        $insert->execute([$title, $description]);
        return (int) $this->connect->lastInsertId();
    }

    /** Trouve un ingrédient par son ID ou son nom; crée-le si le formulaire fournit un nouvel ingrédient. */
    private function resolveIngredientId(array $ingredient): int
    {
        $id = filter_var($ingredient['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id !== false && $id > 0) {
            $stmt = $this->connect->prepare("SELECT `Id` FROM `Ingredient` WHERE `Id` = ?");
            $stmt->execute([$id]);
            $foundId = $stmt->fetchColumn();
            $stmt->closeCursor();
            if ($foundId === false) {
                throw new InvalidArgumentException("L'ingrédient sélectionné n'existe pas");
            }
            return (int) $foundId;
        }

        $name = trim((string) ($ingredient['name'] ?? ''));
        if ($name === '' || strlen($name) > 100) {
            throw new InvalidArgumentException("Le nom de l'ingrédient doit faire de 1 à 100 caractères");
        }

        // Le nom évite de créer un doublon si l'ingrédient est déjà enregistré.
        $find = $this->connect->prepare("SELECT `Id` FROM `Ingredient` WHERE `Name` = ? LIMIT 1");
        $find->execute([$name]);
        $existingId = $find->fetchColumn();
        $find->closeCursor();
        if ($existingId !== false) {
            return (int) $existingId;
        }

        $image = $ingredient['image'] ?? null;
        if ($image !== null && (!is_string($image) || strlen($image) > 200)) {
            throw new InvalidArgumentException("Le chemin de l'image de l'ingrédient ne doit pas dépasser 200 caractères");
        }

        $insert = $this->connect->prepare("INSERT INTO `Ingredient` (`Name`, `image`) VALUES (?, ?)");
        $insert->execute([$name, $image]);
        return (int) $this->connect->lastInsertId();
    }

    /** Construit le SELECT avec des alias compris par RecetteMapping et AbstractMapping. */
    private function recipeSelectSql(): string
    {
        return "SELECT
                    `Id` AS `recette_id`,
                    `Title` AS `recette_title`,
                    `Description` AS `recette_description`,
                    `Image` AS `recette_image`,
                    `duration_prep` AS `recette_duration_prep`,
                    `duration_cook` AS `recette_duration_cook`,
                    `difficulty` AS `recette_difficulty`,
                    `Created_at` AS `recette_date_create`,
                    `Users_Id` AS `recette_user_id`
                FROM `Recipe`";
    }

    /** Vérifie la présence des champs principaux avant de construire le mapping. */
    private function hasRequiredRecipeFields(array $data): bool
    {
        foreach ([
            'recette_title', 'recette_description', 'recette_image',
            'recette_duration_prep', 'recette_duration_cook', 'recette_difficulty',
        ] as $field) {
            if (!isset($data[$field]) || !is_string($data[$field]) || trim($data[$field]) === '') {
                return false;
            }
        }

        return strlen($data['recette_image']) <= 300
            && strlen($data['recette_duration_prep']) <= 45
            && strlen($data['recette_duration_cook']) <= 45
            && strlen($data['recette_difficulty']) <= 45;
    }
}
