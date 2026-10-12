<?php

// Revalide le rôle et l'identité même si ce contrôleur privé est appelé directement.
if (!isset($_SESSION['user_role_id']) || (int) $_SESSION['user_role_id'] !== 1
    || !isset($_SESSION['user_id']) || (int) $_SESSION['user_id'] <= 0) {
    http_response_code(403);
    require_once BASE_URL . '/view/publicView/404View.php';
    return;
}

// Charge les mappings nécessaires au manager de création de recettes.
require_once BASE_URL . '/model/ManagerInterface.php';
require_once BASE_URL . '/model/AbstractMapping.php';
require_once BASE_URL . '/model/StringTrait.php';
require_once BASE_URL . '/model/mapping/userMapping.php';
require_once BASE_URL . '/model/mapping/recetteMapping.php';
require_once BASE_URL . '/model/mapping/categoryMapping.php';
require_once BASE_URL . '/model/mapping/ingredientMapping.php';
require_once BASE_URL . '/model/mapping/stepsMapping.php';
require_once BASE_URL . '/model/manager/RecetteManager.php';

$recipeManager = new model\manager\RecetteManager($connectPDO);
$recipeFormAction = './?pages=admin&action=new-recipe';
$recipeFormCsrfToken = $_SESSION['admin_recipe_csrf_token'] ?? null;
if (!is_string($recipeFormCsrfToken) || strlen($recipeFormCsrfToken) !== 64) {
    $recipeFormCsrfToken = bin2hex(random_bytes(32));
    $_SESSION['admin_recipe_csrf_token'] = $recipeFormCsrfToken;
}

// Convertit les champs attendus en chaînes simples pour réafficher le formulaire sans risque.
$toFormString = static fn($value): string => is_scalar($value) ? (string) $value : '';
$normalizeRows = static function ($rows, array $fields): array {
    if (!is_array($rows)) {
        return [];
    }
    $normalized = [];
    foreach (array_slice($rows, 0, 50) as $row) {
        if (!is_array($row)) {
            continue;
        }
        $safeRow = [];
        foreach ($fields as $field) {
            $value = $row[$field] ?? '';
            $safeRow[$field] = is_scalar($value) ? (string) $value : '';
        }
        $normalized[] = $safeRow;
    }
    return array_values($normalized);
};

$recipeFormValues = [
    'recette_title' => '', 'recette_description' => '', 'recette_image' => '',
    'recette_duration_prep' => '', 'recette_duration_cook' => '',
    'recette_difficulty' => 'Facile', 'portions' => '1',
    'category_ids' => [], 'new_categories' => [], 'new_category_options' => [], 'new_category' => '',
    'new_ingredients' => [], 'new_ingredient_name' => '', 'new_ingredient_image' => '',
    'ingredients' => [['id' => '', 'quantite' => '', 'unit' => 'g']],
    'steps' => [['title' => '', 'description' => '', 'image' => '']],
];
$recipeFormFeedback = null;
$showRecipeForm = (($_GET['action'] ?? '') === 'new-recipe');

// Traite la suppression en POST avec le même jeton CSRF que le formulaire admin.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
    && ($_POST['admin_action'] ?? '') === 'delete_recipe') {
    $postedToken = $_POST['csrf_token'] ?? null;
    $recipeId = filter_var($_POST['recipe_id'] ?? null, FILTER_VALIDATE_INT);

    if (!is_string($postedToken) || !hash_equals($recipeFormCsrfToken, $postedToken)
        || $recipeId === false || $recipeId <= 0) {
        $_SESSION['admin_recipe_feedback'] = [
            'type' => 'error',
            'message' => 'La demande de suppression est invalide ou a expiré. Réessayez depuis la liste des recettes.',
        ];
    } elseif ($recipeManager->deleteRecipe($recipeId)) {
        $_SESSION['admin_recipe_feedback'] = [
            'type' => 'success',
            'message' => 'La recette et ses données associées ont été supprimées.',
        ];
    } else {
        $_SESSION['admin_recipe_feedback'] = [
            'type' => 'error',
            'message' => 'La recette n’a pas pu être supprimée. Elle a peut-être déjà été retirée ou une relation bloque la suppression.',
        ];
    }

    header('Location: ./?pages=admin');
    exit;
}

// Traite le POST avant tout rendu; l'auteur est toujours l'administrateur de session.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
    && ($_POST['admin_action'] ?? '') === 'create_recipe') {
    $showRecipeForm = true;
    $recipeFormValues = [
        'recette_title' => $toFormString($_POST['recette_title'] ?? ''),
        'recette_description' => $toFormString($_POST['recette_description'] ?? ''),
        'recette_image' => $toFormString($_POST['recette_image'] ?? ''),
        'recette_duration_prep' => $toFormString($_POST['recette_duration_prep'] ?? ''),
        'recette_duration_cook' => $toFormString($_POST['recette_duration_cook'] ?? ''),
        'recette_difficulty' => $toFormString($_POST['recette_difficulty'] ?? 'Facile'),
        'portions' => $toFormString($_POST['portions'] ?? '1'),
        'category_ids' => is_array($_POST['category_ids'] ?? null)
            ? array_values(array_map($toFormString, $_POST['category_ids'])) : [],
        'new_categories' => is_array($_POST['new_categories'] ?? null)
            ? array_values(array_map($toFormString, array_slice($_POST['new_categories'], 0, 50))) : [],
        'new_category_options' => is_array($_POST['new_category_options'] ?? null)
            ? array_values(array_map($toFormString, array_slice($_POST['new_category_options'], 0, 50))) : [],
        'new_category' => $toFormString($_POST['new_category'] ?? ''),
        'new_ingredients' => $normalizeRows($_POST['new_ingredients'] ?? null, ['name', 'image']),
        'new_ingredient_name' => $toFormString($_POST['new_ingredient_name'] ?? ''),
        'new_ingredient_image' => $toFormString($_POST['new_ingredient_image'] ?? ''),
        'ingredients' => $normalizeRows($_POST['ingredients'] ?? null, ['id', 'quantite', 'unit']),
        'steps' => $normalizeRows($_POST['steps'] ?? null, ['title', 'description', 'image']),
    ];
    if ($recipeFormValues['ingredients'] === []) {
        $recipeFormValues['ingredients'] = [['id' => '', 'quantite' => '', 'unit' => 'g']];
    }
    if ($recipeFormValues['steps'] === []) {
        $recipeFormValues['steps'] = [['title' => '', 'description' => '', 'image' => '']];
    }

    $postedToken = $_POST['csrf_token'] ?? null;
    if (!is_string($postedToken) || !hash_equals($recipeFormCsrfToken, $postedToken)) {
        $recipeFormFeedback = ['type' => 'error', 'message' => 'Le formulaire a expiré. Rechargez la page puis réessayez.'];
    } else {
        $errors = [];
        $title = trim($recipeFormValues['recette_title']);
        $description = trim($recipeFormValues['recette_description']);
        $image = trim($recipeFormValues['recette_image']);
        $prep = trim($recipeFormValues['recette_duration_prep']);
        $cook = trim($recipeFormValues['recette_duration_cook']);
        $difficulty = $recipeFormValues['recette_difficulty'];
        $portions = filter_var($recipeFormValues['portions'], FILTER_VALIDATE_INT);

        if (strlen($title) < 3 || strlen($title) > 100) {
            $errors[] = 'Le nom doit contenir entre 3 et 100 caractères.';
        }
        if ($description === '' || strlen($description) > 10000) {
            $errors[] = 'La description est obligatoire et limitée à 10 000 caractères.';
        }
        if (strlen($image) > 300) {
            $errors[] = 'Le chemin de l’image ne doit pas dépasser 300 caractères.';
        }
        if ($prep === '' || strlen($prep) > 45 || $cook === '' || strlen($cook) > 45) {
            $errors[] = 'Les durées de préparation et de cuisson sont obligatoires (45 caractères maximum).';
        }
        if (!in_array($difficulty, ['Facile', 'Moyen', 'Difficile'], true)) {
            $errors[] = 'Choisissez un niveau de difficulté proposé.';
        }
        if ($portions === false || $portions < 1) {
            $errors[] = 'Le nombre de portions doit être un entier supérieur à zéro.';
        }

        // Réunit les catégories existantes sélectionnées et les nouvelles cochées dans la liste.
        $categories = [];
        foreach ($recipeFormValues['category_ids'] as $categoryIdValue) {
            $categoryId = filter_var($categoryIdValue, FILTER_VALIDATE_INT);
            if ($categoryId === false || $categoryId <= 0) {
                $errors[] = 'Une catégorie sélectionnée est invalide.';
                break;
            }
            $categories[] = ['id' => $categoryId];
        }
        foreach ($recipeFormValues['new_categories'] as $newCategory) {
            $newCategory = trim($newCategory);
            if ($newCategory === '' || strlen($newCategory) > 100) {
                $errors[] = 'Le nom de chaque nouvelle catégorie doit contenir de 1 à 100 caractères.';
                continue;
            }
            $categories[] = ['title' => $newCategory];
        }
        if (trim($recipeFormValues['new_category']) !== '') {
            $errors[] = 'Cliquez sur « Ajouter » pour placer la nouvelle catégorie dans la liste avant d’enregistrer.';
        }
        if ($categories === []) {
            $errors[] = 'Cochez au moins une catégorie dans la liste.';
        }

        // Contrôle les quantités numériques et n'accepte que les unités offertes par la liste.
        $allowedUnits = ['g', 'kg', 'mg', 'ml', 'cl', 'l', 'unité', 'pincée', 'c. à café', 'c. à soupe', 'sachet'];
        $ingredients = [];
        foreach ($recipeFormValues['ingredients'] as $ingredient) {
            $selection = $ingredient['id'];
            $quantity = trim($ingredient['quantite']);
            $unit = $ingredient['unit'];
            if ($selection === '' && $quantity === '') {
                continue;
            }
            $normalizedQuantity = str_replace(',', '.', $quantity);
            if (!preg_match('/^(?:[0-9]+(?:[.][0-9]+)?|[.][0-9]+)$/', $normalizedQuantity)
                || (float) $normalizedQuantity <= 0 || strlen($normalizedQuantity) > 45
                || !in_array($unit, $allowedUnits, true)) {
                $errors[] = 'Vérifiez la sélection, la quantité positive et la mesure de chaque ingrédient.';
                break;
            }

            // Les valeurs new:N désignent les ingrédients ajoutés à la liste par ce formulaire.
            if (preg_match('/^new:([0-9]+)$/', $selection, $selectionMatch) === 1) {
                $definitionIndex = filter_var($selectionMatch[1], FILTER_VALIDATE_INT);
                $newIngredient = $definitionIndex === false
                    ? null
                    : ($recipeFormValues['new_ingredients'][$definitionIndex] ?? null);
                if (!is_array($newIngredient)) {
                    $errors[] = 'Un nouvel ingrédient sélectionné est introuvable. Ajoutez-le de nouveau à la liste.';
                    break;
                }
                $ingredientName = trim($newIngredient['name']);
                $ingredientImage = trim($newIngredient['image']);
                $imageScheme = strtolower((string) parse_url($ingredientImage, PHP_URL_SCHEME));
                $validImageLink = $ingredientImage === ''
                    || (filter_var($ingredientImage, FILTER_VALIDATE_URL) !== false
                        && in_array($imageScheme, ['http', 'https'], true));
                if ($ingredientName === '' || strlen($ingredientName) > 100
                    || strlen($ingredientImage) > 200 || !$validImageLink) {
                    $errors[] = 'Le nom du nouvel ingrédient et son lien d’image ne sont pas valides.';
                    break;
                }
                $ingredientData = [
                    'name' => $ingredientName,
                    'image' => $ingredientImage === '' ? null : $ingredientImage,
                ];
            } else {
                $ingredientId = filter_var($selection, FILTER_VALIDATE_INT);
                if ($ingredientId === false || $ingredientId <= 0) {
                    $errors[] = 'Sélectionnez un ingrédient valide dans chaque ligne.';
                    break;
                }
                $ingredientData = ['id' => $ingredientId];
            }

            $ingredients[] = $ingredientData + [
                'quantite' => $normalizedQuantity,
                'unit' => $unit,
            ];
        }
        if (trim($recipeFormValues['new_ingredient_name']) !== ''
            || trim($recipeFormValues['new_ingredient_image']) !== '') {
            $errors[] = 'Cliquez sur « Ajouter à la liste » pour inclure le nouvel ingrédient avant d’enregistrer.';
        }
        if ($ingredients === []) {
            $errors[] = 'Ajoutez au moins un ingrédient avec quantité et mesure.';
        }

        $steps = [];
        foreach ($recipeFormValues['steps'] as $step) {
            $stepTitle = trim($step['title']);
            $stepDescription = trim($step['description']);
            $stepImage = trim($step['image']);
            if ($stepTitle === '' && $stepDescription === '' && $stepImage === '') {
                continue;
            }
            if ($stepTitle === '' || strlen($stepTitle) > 100
                || $stepDescription === '' || strlen($stepDescription) > 200
                || strlen($stepImage) > 300) {
                $errors[] = 'Chaque étape doit avoir un titre et une description (200 caractères maximum).';
                break;
            }
            $steps[] = ['title' => $stepTitle, 'description' => $stepDescription, 'image' => $stepImage];
        }
        if ($steps === []) {
            $errors[] = 'Ajoutez au moins une étape avec un titre et une description.';
        }

        if ($errors !== []) {
            $recipeFormFeedback = ['type' => 'error', 'message' => implode(' ', array_unique($errors))];
        } else {
            $createdRecipe = $recipeManager->createRecipe([
                'recette_title' => $title,
                'recette_description' => $description,
                'recette_image' => $image,
                'recette_duration_prep' => $prep,
                'recette_duration_cook' => $cook,
                'recette_difficulty' => $difficulty,
                'portions' => $portions,
                'categories' => $categories,
                'ingredients' => $ingredients,
                'steps' => $steps,
            ], (int) $_SESSION['user_id']);

            if ($createdRecipe === false) {
                $recipeFormFeedback = ['type' => 'error', 'message' => 'La recette n’a pas pu être enregistrée. Vérifiez les champs puis réessayez.'];
            } else {
                $_SESSION['admin_recipe_feedback'] = [
                    'type' => 'success',
                    'message' => 'La recette a été créée avec succès.',
                    'slug' => $createdRecipe->getRecetteSlug(),
                ];
                header('Location: ./?pages=admin');
                exit;
            }
        }
    }
}

$adminFeedback = $_SESSION['admin_recipe_feedback'] ?? null;
unset($_SESSION['admin_recipe_feedback']);
$categories = $showRecipeForm ? $recipeManager->getAllCategories() : [];
$availableIngredients = $showRecipeForm ? $recipeManager->getAllIngredients() : [];
$adminRecipes = $showRecipeForm ? [] : $recipeManager->getAllRecettes();

// Charge le menu privé commun avant le tableau de bord ou le formulaire.
require_once BASE_URL . '/view/privateView/inc/menuPrivateView.php';
if ($showRecipeForm) {
    require BASE_URL . '/view/privateView/newRecipe.php';
} else {
    require BASE_URL . '/view/privateView/admin.php';
}
