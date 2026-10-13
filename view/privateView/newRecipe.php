<?php
// Échappe toutes les valeurs issues de la base ou d'une précédente soumission du formulaire.
$escapeAdminRecipe = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
$unitOptions = ['g', 'kg', 'mg', 'ml', 'cl', 'l', 'unité', 'pincée', 'c. à café', 'c. à soupe', 'sachet'];
$ingredientRows = array_values($recipeFormValues['ingredients'] ?? []);
$newIngredientDefinitions = array_values($recipeFormValues['new_ingredients'] ?? []);
$stepRows = array_values($recipeFormValues['steps'] ?? []);
$selectedCategoryIds = $recipeFormValues['category_ids'] ?? [];
$addedCategoryNames = $recipeFormValues['new_category_options'] ?? [];
$selectedNewCategoryNames = $recipeFormValues['new_categories'] ?? [];
?>
<main class="min-h-screen bg-background px-4 pb-16 pt-32 text-on-background sm:px-6">
    <section class="mx-auto max-w-6xl overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm">
        <header class="bg-[#2F1B14] px-6 py-10 text-center text-surface sm:px-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-[#fed488]">Administration · Carnet culinaire</p>
            <h1 class="font-headline-lg text-4xl tracking-tight">Nouvelle recette</h1>
            <p class="mx-auto mt-3 max-w-2xl text-surface-variant">Renseignez la fiche, ses ingrédients, ses étapes et ses catégories.</p>
        </header>

        <div class="space-y-8 p-5 sm:p-10">
            <a class="inline-flex items-center gap-2 text-sm font-semibold text-secondary hover:underline" href="./?pages=admin">
                <span aria-hidden="true">←</span> Retour à l’administration
            </a>

            <?php if (is_array($recipeFormFeedback) && isset($recipeFormFeedback['message'])): ?>
                <p class="rounded-lg border px-4 py-3 <?= ($recipeFormFeedback['type'] ?? '') === 'success' ? 'border-green-700 bg-green-50 text-green-800' : 'border-red-700 bg-red-50 text-red-800' ?>" role="alert">
                    <?= $escapeAdminRecipe($recipeFormFeedback['message']) ?>
                </p>
            <?php endif; ?>

            <?php if ($availableIngredients === []): ?>
                <p class="rounded-lg border border-amber-700 bg-amber-50 px-4 py-3 text-amber-900" role="status">
                    Aucun ingrédient existant. Ajoutez un nouvel ingrédient dans le formulaire ci-dessous; il sera enregistré en base avec la recette.
                </p>
            <?php endif; ?>

            <form action="<?= $escapeAdminRecipe($recipeFormAction) ?>" class="space-y-8" enctype="multipart/form-data" method="post">
                <input name="csrf_token" type="hidden" value="<?= $escapeAdminRecipe($recipeFormCsrfToken) ?>">
                <input name="admin_action" type="hidden" value="create_recipe">

                <section class="space-y-5" aria-labelledby="recipe-main-fields">
                    <div class="border-b border-[#C9A45C] pb-2">
                        <h2 class="text-2xl font-bold font-['Playfair_Display']" id="recipe-main-fields">Informations de la recette</h2>
                    </div>
                    <div class="grid gap-5 md:grid-cols-2">
                        <label class="space-y-1 text-sm font-semibold" for="recette-title">
                            Nom de la recette
                            <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="recette-title" maxlength="100" name="recette_title" required type="text" value="<?= $escapeAdminRecipe($recipeFormValues['recette_title']) ?>">
                        </label>
                        <label class="space-y-1 text-sm font-semibold" for="recette-image">
                            Image de la recette <span class="font-normal">(facultative · .webp, .jpg ou .jpeg · 300 Ko maximum)</span>
                            <input accept=".webp,.jpg,.jpeg,image/webp,image/jpeg" class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="recette-image" name="recette_image_file" type="file">
                        </label>
                    </div>
                    <label class="block space-y-1 text-sm font-semibold" for="recette-description">
                        Description
                        <textarea class="min-h-32 w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="recette-description" maxlength="10000" name="recette_description" required><?= $escapeAdminRecipe($recipeFormValues['recette_description']) ?></textarea>
                    </label>
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                        <label class="space-y-1 text-sm font-semibold" for="recette-prep">
                            Préparation
                            <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="recette-prep" maxlength="45" name="recette_duration_prep" placeholder="20 min" required type="text" value="<?= $escapeAdminRecipe($recipeFormValues['recette_duration_prep']) ?>">
                        </label>
                        <label class="space-y-1 text-sm font-semibold" for="recette-cook">
                            Cuisson
                            <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="recette-cook" maxlength="45" name="recette_duration_cook" placeholder="30 min" required type="text" value="<?= $escapeAdminRecipe($recipeFormValues['recette_duration_cook']) ?>">
                        </label>
                        <label class="space-y-1 text-sm font-semibold" for="recette-difficulty">
                            Difficulté
                            <select class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="recette-difficulty" name="recette_difficulty" required>
                                <?php foreach (['Facile', 'Moyen', 'Difficile'] as $difficulty): ?>
                                    <option value="<?= $escapeAdminRecipe($difficulty) ?>" <?= $recipeFormValues['recette_difficulty'] === $difficulty ? 'selected' : '' ?>><?= $escapeAdminRecipe($difficulty) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="space-y-1 text-sm font-semibold" for="recipe-portions">
                            Portions
                            <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="recipe-portions" min="1" name="portions" required type="number" value="<?= $escapeAdminRecipe($recipeFormValues['portions']) ?>">
                        </label>
                    </div>
                </section>

                <section class="space-y-4" aria-labelledby="recipe-categories-heading">
                    <div class="border-b border-[#C9A45C] pb-2">
                        <h2 class="text-2xl font-bold font-['Playfair_Display']" id="recipe-categories-heading">Catégories</h2>
                        <p class="mt-1 text-sm text-on-surface-variant">Cochez toutes les catégories à associer à la recette. Une catégorie ajoutée apparaîtra ici cochée.</p>
                    </div>
                    <div role="group" aria-labelledby="category-options-label">
                        <p class="mb-2 text-sm font-semibold" id="category-options-label">Catégories disponibles</p>
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3" id="category-options">
                            <?php if ($categories === [] && $addedCategoryNames === []): ?>
                                <p class="text-sm text-on-surface-variant">Aucune catégorie pour le moment. Ajoutez-en une ci-dessous.</p>
                            <?php endif; ?>
                            <?php foreach ($categories as $category): ?>
                                <?php $categoryId = (string) $category->getId(); ?>
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-3 hover:bg-[#F8EDD3]" data-category-name="<?= $escapeAdminRecipe($category->getTitle()) ?>">
                                    <input class="h-4 w-4 accent-[#775A19]" name="category_ids[]" type="checkbox" value="<?= $escapeAdminRecipe($categoryId) ?>" <?= in_array($categoryId, $selectedCategoryIds, true) ? 'checked' : '' ?>>
                                    <span><?= $escapeAdminRecipe($category->getTitle()) ?></span>
                                </label>
                            <?php endforeach; ?>
                            <?php foreach ($addedCategoryNames as $addedCategoryName): ?>
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-3 hover:bg-[#F8EDD3]" data-category-name="<?= $escapeAdminRecipe($addedCategoryName) ?>">
                                    <input class="h-4 w-4 accent-[#775A19]" name="new_categories[]" type="checkbox" value="<?= $escapeAdminRecipe($addedCategoryName) ?>" <?= in_array($addedCategoryName, $selectedNewCategoryNames, true) ? 'checked' : '' ?>>
                                    <span><?= $escapeAdminRecipe($addedCategoryName) ?></span>
                                    <span class="ml-auto rounded bg-[#E5C98A] px-2 py-0.5 text-xs">Nouvelle</span>
                                    <input name="new_category_options[]" type="hidden" value="<?= $escapeAdminRecipe($addedCategoryName) ?>">
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                        <label class="flex-1 space-y-1 text-sm font-semibold" for="recipe-new-category">
                            Ajouter une catégorie à la liste
                            <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="recipe-new-category" maxlength="100" name="new_category" placeholder="Ex. Biscuits" type="text" value="<?= $escapeAdminRecipe($recipeFormValues['new_category']) ?>">
                        </label>
                        <button class="rounded bg-[#2F1B14] px-5 py-2 font-semibold text-white hover:bg-secondary" data-add-category type="button">Ajouter</button>
                    </div>
                    <p aria-live="polite" class="text-sm text-on-surface-variant" id="category-add-feedback">Une catégorie ne sera enregistrée en base que si sa case reste cochée et que la recette est enregistrée.</p>
                </section>

                <section class="space-y-4" aria-labelledby="recipe-ingredients-heading">
                    <div class="flex flex-wrap items-end justify-between gap-3 border-b border-[#C9A45C] pb-2">
                        <div>
                            <h2 class="text-2xl font-bold font-['Playfair_Display']" id="recipe-ingredients-heading">Ingrédients</h2>
                            <p class="mt-1 text-sm text-on-surface-variant">Choisissez un ingrédient, saisissez une quantité numérique et sélectionnez une mesure.</p>
                        </div>
                        <button class="rounded bg-[#2F1B14] px-4 py-2 text-sm font-semibold text-white hover:bg-secondary" data-add-ingredient type="button">+ Ajouter un ingrédient</button>
                    </div>
                    <div class="space-y-4 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-4">
                        <div>
                            <h3 class="font-semibold">Ajouter un ingrédient absent de la liste</h3>
                            <p class="mt-1 text-sm text-on-surface-variant">Il sera ajouté aux choix et à une nouvelle ligne. Son enregistrement en base se fera avec la recette.</p>
                        </div>
                        <div class="grid items-end gap-3 md:grid-cols-[1fr_1fr_auto]">
                            <label class="space-y-1 text-sm font-semibold" for="new-ingredient-name">
                                Nom de l’ingrédient
                                <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="new-ingredient-name" maxlength="100" name="new_ingredient_name" placeholder="Ex. Farine de noisette" type="text" value="<?= $escapeAdminRecipe($recipeFormValues['new_ingredient_name']) ?>">
                            </label>
                            <label class="space-y-1 text-sm font-semibold" for="new-ingredient-image">
                                Image de l’ingrédient <span class="font-normal">(facultative · .webp, .jpg ou .jpeg · 300 Ko maximum)</span>
                                <input accept=".webp,.jpg,.jpeg,image/webp,image/jpeg" class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="new-ingredient-image" type="file">
                            </label>
                            <button class="rounded bg-[#2F1B14] px-5 py-2 font-semibold text-white hover:bg-secondary" data-add-new-ingredient type="button">Ajouter à la liste</button>
                        </div>
                        <p aria-live="polite" class="text-sm text-on-surface-variant" id="new-ingredient-feedback">Le nouvel ingrédient sera sélectionné automatiquement; indiquez ensuite sa quantité et sa mesure.</p>
                        <div class="hidden" id="new-ingredient-definitions" aria-hidden="true">
                            <?php foreach ($newIngredientDefinitions as $newIngredientIndex => $newIngredient): ?>
                                <div data-new-ingredient-definition>
                                    <input data-new-ingredient-name name="new_ingredients[<?= $newIngredientIndex ?>][name]" type="hidden" value="<?= $escapeAdminRecipe($newIngredient['name']) ?>">
                                    <input accept=".webp,.jpg,.jpeg,image/webp,image/jpeg" class="hidden" name="new_ingredient_images[<?= $newIngredientIndex ?>]" type="file">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="space-y-3" id="ingredient-rows">
                        <?php foreach ($ingredientRows as $index => $ingredientRow): ?>
                            <div class="grid items-end gap-3 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-4 sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_auto]" data-dynamic-row>
                                <label class="space-y-1 text-sm font-semibold" for="ingredient-<?= $index ?>-id">
                                    Ingrédient
                                    <select class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="ingredient-<?= $index ?>-id" name="ingredients[<?= $index ?>][id]" required>
                                        <option value="">Choisir un ingrédient</option>
                                        <?php foreach ($availableIngredients as $availableIngredient): ?>
                                            <option data-ingredient-name="<?= $escapeAdminRecipe($availableIngredient->getName()) ?>" value="<?= (int) $availableIngredient->getId() ?>" <?= (string) $ingredientRow['id'] === (string) $availableIngredient->getId() ? 'selected' : '' ?>><?= $escapeAdminRecipe($availableIngredient->getName()) ?></option>
                                        <?php endforeach; ?>
                                        <?php foreach ($newIngredientDefinitions as $newIngredientIndex => $newIngredient): ?>
                                            <option data-ingredient-name="<?= $escapeAdminRecipe($newIngredient['name']) ?>" value="new:<?= $newIngredientIndex ?>" <?= $ingredientRow['id'] === 'new:' . $newIngredientIndex ? 'selected' : '' ?>><?= $escapeAdminRecipe($newIngredient['name']) ?> (nouveau)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="space-y-1 text-sm font-semibold" for="ingredient-<?= $index ?>-quantity">
                                    Quantité
                                    <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="ingredient-<?= $index ?>-quantity" min="0.01" name="ingredients[<?= $index ?>][quantite]" required step="any" type="number" value="<?= $escapeAdminRecipe($ingredientRow['quantite']) ?>">
                                </label>
                                <label class="space-y-1 text-sm font-semibold" for="ingredient-<?= $index ?>-unit">
                                    Mesure
                                    <select class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="ingredient-<?= $index ?>-unit" name="ingredients[<?= $index ?>][unit]" required>
                                        <?php foreach ($unitOptions as $unit): ?>
                                            <option value="<?= $escapeAdminRecipe($unit) ?>" <?= $ingredientRow['unit'] === $unit ? 'selected' : '' ?>><?= $escapeAdminRecipe($unit) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <button aria-label="Retirer cet ingrédient" class="rounded border border-red-700 px-3 py-2 text-sm text-red-800 hover:bg-red-50" data-remove-row type="button">Retirer</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="space-y-4" aria-labelledby="recipe-steps-heading">
                    <div class="flex flex-wrap items-end justify-between gap-3 border-b border-[#C9A45C] pb-2">
                        <div>
                            <h2 class="text-2xl font-bold font-['Playfair_Display']" id="recipe-steps-heading">Étapes de préparation</h2>
                            <p class="mt-1 text-sm text-on-surface-variant">Ajoutez au moins une étape; le titre et la description sont requis.</p>
                        </div>
                        <button class="rounded bg-[#2F1B14] px-4 py-2 text-sm font-semibold text-white hover:bg-secondary" data-add-step type="button">+ Ajouter une étape</button>
                    </div>
                    <div class="space-y-3" id="step-rows">
                        <?php foreach ($stepRows as $index => $stepRow): ?>
                            <fieldset class="space-y-3 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-4" data-dynamic-row>
                                <legend class="px-2 font-semibold">Étape <?= $index + 1 ?></legend>
                                <div class="grid gap-3 md:grid-cols-2">
                                    <label class="space-y-1 text-sm font-semibold" for="step-<?= $index ?>-title">
                                        Titre
                                        <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="step-<?= $index ?>-title" maxlength="100" name="steps[<?= $index ?>][title]" required type="text" value="<?= $escapeAdminRecipe($stepRow['title']) ?>">
                                    </label>
                                    <label class="space-y-1 text-sm font-semibold" for="step-<?= $index ?>-image">
                                        Image de l’étape <span class="font-normal">(facultative · .webp, .jpg ou .jpeg · 300 Ko maximum)</span>
                                        <input accept=".webp,.jpg,.jpeg,image/webp,image/jpeg" class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="step-<?= $index ?>-image" name="step_images[<?= $index ?>]" type="file">
                                    </label>
                                </div>
                                <label class="block space-y-1 text-sm font-semibold" for="step-<?= $index ?>-description">
                                    Description
                                    <textarea class="min-h-24 w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="step-<?= $index ?>-description" maxlength="200" name="steps[<?= $index ?>][description]" required><?= $escapeAdminRecipe($stepRow['description']) ?></textarea>
                                </label>
                                <button aria-label="Retirer cette étape" class="rounded border border-red-700 px-3 py-2 text-sm text-red-800 hover:bg-red-50" data-remove-row type="button">Retirer l’étape</button>
                            </fieldset>
                        <?php endforeach; ?>
                    </div>
                </section>

                <div class="flex flex-wrap justify-end gap-3 border-t border-[#C9A45C] pt-6">
                    <a class="rounded border border-[#2F1B14] px-5 py-2 font-semibold text-[#2F1B14] hover:bg-[#FBF5E6]" href="./?pages=admin">Annuler</a>
                    <button class="rounded bg-[#2F1B14] px-6 py-2 font-semibold text-white shadow-sm hover:bg-secondary" type="submit">Enregistrer la recette</button>
                </div>
            </form>
        </div>
    </section>
</main>

<template id="ingredient-template">
    <div class="grid items-end gap-3 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-4 sm:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_auto]" data-dynamic-row>
        <label class="space-y-1 text-sm font-semibold" for="ingredient-__INDEX__-id">
            Ingrédient
            <select class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="ingredient-__INDEX__-id" name="ingredients[__INDEX__][id]" required>
                <option value="">Choisir un ingrédient</option>
                <?php foreach ($availableIngredients as $availableIngredient): ?>
                    <option data-ingredient-name="<?= $escapeAdminRecipe($availableIngredient->getName()) ?>" value="<?= (int) $availableIngredient->getId() ?>"><?= $escapeAdminRecipe($availableIngredient->getName()) ?></option>
                <?php endforeach; ?>
                <?php foreach ($newIngredientDefinitions as $newIngredientIndex => $newIngredient): ?>
                    <option data-ingredient-name="<?= $escapeAdminRecipe($newIngredient['name']) ?>" value="new:<?= $newIngredientIndex ?>"><?= $escapeAdminRecipe($newIngredient['name']) ?> (nouveau)</option>
                <?php endforeach; ?>
            </select>
        </label>
        <label class="space-y-1 text-sm font-semibold" for="ingredient-__INDEX__-quantity">
            Quantité
            <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="ingredient-__INDEX__-quantity" min="0.01" name="ingredients[__INDEX__][quantite]" required step="any" type="number">
        </label>
        <label class="space-y-1 text-sm font-semibold" for="ingredient-__INDEX__-unit">
            Mesure
            <select class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="ingredient-__INDEX__-unit" name="ingredients[__INDEX__][unit]" required>
                <?php foreach ($unitOptions as $unit): ?>
                    <option value="<?= $escapeAdminRecipe($unit) ?>" <?= $unit === 'g' ? 'selected' : '' ?>><?= $escapeAdminRecipe($unit) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button aria-label="Retirer cet ingrédient" class="rounded border border-red-700 px-3 py-2 text-sm text-red-800 hover:bg-red-50" data-remove-row type="button">Retirer</button>
    </div>
</template>

<template id="step-template">
    <fieldset class="space-y-3 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-4" data-dynamic-row>
        <legend class="px-2 font-semibold">Étape __NUMBER__</legend>
        <div class="grid gap-3 md:grid-cols-2">
            <label class="space-y-1 text-sm font-semibold" for="step-__INDEX__-title">
                Titre
                <input class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="step-__INDEX__-title" maxlength="100" name="steps[__INDEX__][title]" required type="text">
            </label>
            <label class="space-y-1 text-sm font-semibold" for="step-__INDEX__-image">
                Image de l’étape <span class="font-normal">(facultative · .webp, .jpg ou .jpeg · 300 Ko maximum)</span>
                <input accept=".webp,.jpg,.jpeg,image/webp,image/jpeg" class="w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="step-__INDEX__-image" name="step_images[__INDEX__]" type="file">
            </label>
        </div>
        <label class="block space-y-1 text-sm font-semibold" for="step-__INDEX__-description">
            Description
            <textarea class="min-h-24 w-full rounded border border-[#C9A45C] bg-white px-3 py-2 font-normal" id="step-__INDEX__-description" maxlength="200" name="steps[__INDEX__][description]" required></textarea>
        </label>
        <button aria-label="Retirer cette étape" class="rounded border border-red-700 px-3 py-2 text-sm text-red-800 hover:bg-red-50" data-remove-row type="button">Retirer l’étape</button>
    </fieldset>
</template>

<?php require BASE_URL . '/view/publicView/inc/footer.php'; ?>
<script src="./js/scripts.js?v=<?= filemtime(BASE_URL . '/public/js/scripts.js') ?>"></script>
<script>
// Permet d'ajouter et de retirer plusieurs ingrédients et étapes sans recharger le formulaire.
document.addEventListener('DOMContentLoaded', function () {
    const ingredientRows = document.getElementById('ingredient-rows');
    const stepRows = document.getElementById('step-rows');
    const ingredientTemplate = document.getElementById('ingredient-template');
    const stepTemplate = document.getElementById('step-template');
    let nextIngredientIndex = ingredientRows ? ingredientRows.querySelectorAll('[data-dynamic-row]').length : 0;
    let nextStepIndex = stepRows ? stepRows.querySelectorAll('[data-dynamic-row]').length : 0;
    const categoryOptions = document.getElementById('category-options');
    const categoryInput = document.getElementById('recipe-new-category');
    const categoryFeedback = document.getElementById('category-add-feedback');
    const newIngredientDefinitions = document.getElementById('new-ingredient-definitions');
    const newIngredientNameInput = document.getElementById('new-ingredient-name');
    const newIngredientImageInput = document.getElementById('new-ingredient-image');
    const newIngredientFeedback = document.getElementById('new-ingredient-feedback');
    let nextNewIngredientIndex = newIngredientDefinitions
        ? newIngredientDefinitions.querySelectorAll('[data-new-ingredient-definition]').length
        : 0;

    // Ajoute une ligne en conservant aussi les options déjà créées dans ce formulaire.
    function appendIngredientRow(selectedValue = '') {
        if (!ingredientRows || !ingredientTemplate) return null;
        const html = ingredientTemplate.innerHTML.replaceAll('__INDEX__', String(nextIngredientIndex++));
        ingredientRows.insertAdjacentHTML('beforeend', html);
        const row = ingredientRows.lastElementChild;
        const select = row?.querySelector('select[name$="[id]"]');
        if (select && selectedValue) select.value = selectedValue;
        return row;
    }

    // Ajoute un nouvel ingrédient à la liste et associe son image locale au POST.
    function addNewIngredientToList() {
        if (!newIngredientDefinitions || !newIngredientNameInput || !ingredientTemplate) return;
        const name = newIngredientNameInput.value.trim();
        const imageFile = newIngredientImageInput?.files?.[0] || null;
        if (!name) {
            if (newIngredientFeedback) newIngredientFeedback.textContent = 'Saisissez le nom de l’ingrédient avant de l’ajouter.';
            newIngredientNameInput.focus();
            return;
        }
        if (name.length > 100) {
            if (newIngredientFeedback) newIngredientFeedback.textContent = 'Le nom de l’ingrédient ne doit pas dépasser 100 caractères.';
            return;
        }
        if (imageFile) {
            const extension = imageFile.name.split('.').pop().toLowerCase();
            if (!['webp', 'jpg', 'jpeg'].includes(extension) || imageFile.size > 300 * 1024) {
                if (newIngredientFeedback) newIngredientFeedback.textContent = 'Choisissez une image .webp, .jpg ou .jpeg de 300 Ko maximum.';
                newIngredientImageInput?.focus();
                return;
            }
        }
        const normalizeName = value => value.trim().normalize('NFC').toLocaleLowerCase('fr');
        const knownOptions = [...ingredientTemplate.content.querySelectorAll('option[data-ingredient-name]')];
        const duplicate = knownOptions.find(option => normalizeName(option.dataset.ingredientName || '') === normalizeName(name));
        if (duplicate) {
            const alreadyInRecipe = [...(ingredientRows?.querySelectorAll('select[name$="[id]"]') ?? [])]
                .some(select => select.value === duplicate.value);
            if (alreadyInRecipe) {
                if (newIngredientFeedback) newIngredientFeedback.textContent = `« ${name} » est déjà dans une ligne de la recette; modifiez cette ligne plutôt que de l’ajouter deux fois.`;
                newIngredientNameInput.value = '';
                if (newIngredientImageInput) newIngredientImageInput.value = '';
                newIngredientNameInput.focus();
                return;
            }
            appendIngredientRow(duplicate.value);
            if (newIngredientFeedback) newIngredientFeedback.textContent = `« ${name} » existe déjà; l’ingrédient enregistré sera réutilisé sans remplacer son image.`;
        } else {
            if (nextNewIngredientIndex >= 50) {
                if (newIngredientFeedback) newIngredientFeedback.textContent = 'Vous pouvez ajouter jusqu’à 50 nouveaux ingrédients à la fois.';
                return;
            }
            const index = nextNewIngredientIndex++;
            const option = document.createElement('option');
            option.value = `new:${index}`;
            option.dataset.ingredientName = name;
            option.textContent = `${name} (nouveau)`;
            ingredientRows?.querySelectorAll('select[name$="[id]"]').forEach(select => select.append(option.cloneNode(true)));
            ingredientTemplate.content.querySelector('select')?.append(option.cloneNode(true));

            const definition = document.createElement('div');
            definition.dataset.newIngredientDefinition = '';
            const nameField = document.createElement('input');
            nameField.type = 'hidden';
            nameField.name = `new_ingredients[${index}][name]`;
            nameField.value = name;
            definition.appendChild(nameField);
            if (imageFile) {
                const imageField = document.createElement('input');
                imageField.type = 'file';
                imageField.accept = '.webp,.jpg,.jpeg,image/webp,image/jpeg';
                imageField.name = `new_ingredient_images[${index}]`;
                imageField.className = 'hidden';
                const transfer = new DataTransfer();
                transfer.items.add(imageFile);
                imageField.files = transfer.files;
                definition.appendChild(imageField);
            }
            newIngredientDefinitions.appendChild(definition);

            appendIngredientRow(`new:${index}`);
            const imageNotice = imageFile ? ` Image « ${imageFile.name} » jointe.` : '';
            if (newIngredientFeedback) newIngredientFeedback.textContent = `« ${name} » a été ajouté à la liste.${imageNotice} Renseignez sa quantité et sa mesure.`;
        }
        newIngredientNameInput.value = '';
        if (newIngredientImageInput) newIngredientImageInput.value = '';
        newIngredientNameInput.focus();
    }

    document.querySelector('[data-add-new-ingredient]')?.addEventListener('click', addNewIngredientToList);
    newIngredientNameInput?.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            addNewIngredientToList();
        }
    });
    // Ajoute la catégorie saisie comme case cochée; elle sera sauvegardée avec la recette.
    function addCategoryToList() {
        if (!categoryOptions || !categoryInput) return;
        const name = categoryInput.value.trim();
        if (!name) {
            if (categoryFeedback) categoryFeedback.textContent = 'Saisissez le nom de la catégorie avant de cliquer sur Ajouter.';
            categoryInput.focus();
            return;
        }
        if (name.length > 100) {
            if (categoryFeedback) categoryFeedback.textContent = 'Le nom de la catégorie ne doit pas dépasser 100 caractères.';
            categoryInput.focus();
            return;
        }

        const normalizeName = value => value.trim().normalize('NFC').toLocaleLowerCase('fr');
        const duplicate = [...categoryOptions.querySelectorAll('[data-category-name]')]
            .find(label => normalizeName(label.dataset.categoryName || '') === normalizeName(name));
        if (duplicate) {
            const checkbox = duplicate.querySelector('input[type="checkbox"]');
            if (checkbox) checkbox.checked = true;
            if (categoryFeedback) categoryFeedback.textContent = `« ${name} » est déjà dans la liste et vient d’être cochée.`;
        } else {
            const label = document.createElement('label');
            label.className = 'flex cursor-pointer items-center gap-3 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-3 hover:bg-[#F8EDD3]';
            label.dataset.categoryName = name;

            const checkbox = document.createElement('input');
            checkbox.className = 'h-4 w-4 accent-[#775A19]';
            checkbox.type = 'checkbox';
            checkbox.name = 'new_categories[]';
            checkbox.value = name;
            checkbox.checked = true;

            const title = document.createElement('span');
            title.textContent = name;
            const badge = document.createElement('span');
            badge.className = 'ml-auto rounded bg-[#E5C98A] px-2 py-0.5 text-xs';
            badge.textContent = 'Nouvelle';
            const preservedOption = document.createElement('input');
            preservedOption.type = 'hidden';
            preservedOption.name = 'new_category_options[]';
            preservedOption.value = name;

            label.append(checkbox, title, badge, preservedOption);
            categoryOptions.appendChild(label);
            if (categoryFeedback) categoryFeedback.textContent = `« ${name} » a été ajoutée à la liste et cochée.`;
        }
        categoryInput.value = '';
        categoryInput.focus();
    }

    document.querySelector('[data-add-category]')?.addEventListener('click', addCategoryToList);
    categoryInput?.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            addCategoryToList();
        }
    });

    document.querySelector('[data-add-ingredient]')?.addEventListener('click', function () {
        appendIngredientRow();
    });
    document.querySelector('[data-add-step]')?.addEventListener('click', function () {
        if (!stepRows || !stepTemplate) return;
        const index = nextStepIndex++;
        const html = stepTemplate.innerHTML
            .replaceAll('__INDEX__', String(index))
            .replaceAll('__NUMBER__', String(index + 1));
        stepRows.insertAdjacentHTML('beforeend', html);
    });

    [ingredientRows, stepRows].forEach(function (container) {
        container?.addEventListener('click', function (event) {
            const removeButton = event.target.closest('[data-remove-row]');
            if (!removeButton) return;
            const rows = container.querySelectorAll('[data-dynamic-row]');
            if (rows.length > 1) removeButton.closest('[data-dynamic-row]')?.remove();
        });
    });
});
</script>
</body>
</html>
