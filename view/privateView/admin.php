<?php
$escapeAdminDashboard = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
?>
<main class="min-h-screen bg-background px-4 pb-16 pt-32 text-on-background sm:px-6">
    <section class="mx-auto max-w-6xl overflow-hidden rounded-xl bg-surface-container-lowest shadow-sm">
        <header class="bg-[#2F1B14] px-6 py-12 text-center text-surface sm:px-10">
            <p class="mb-2 text-sm font-semibold uppercase tracking-[0.2em] text-[#fed488]">Espace réservé</p>
            <h1 class="font-headline-lg text-4xl tracking-tight">Administration</h1>
            <p class="mx-auto mt-3 max-w-2xl text-surface-variant">Gérez les recettes de la Maison Rosalie depuis cet espace.</p>
        </header>

        <div class="space-y-6 p-6 sm:p-10">
            <?php if (is_array($adminFeedback) && isset($adminFeedback['message'])): ?>
                <div class="rounded-lg border px-4 py-3 <?= ($adminFeedback['type'] ?? '') === 'error' ? 'border-red-700 bg-red-50 text-red-800' : 'border-green-700 bg-green-50 text-green-800' ?>" role="status">
                    <p><?= $escapeAdminDashboard($adminFeedback['message']) ?></p>
                    <?php if (!empty($adminFeedback['slug']) && is_string($adminFeedback['slug'])): ?>
                        <a class="mt-2 inline-block font-semibold underline" href="./?pages=recipe&amp;slug=<?= rawurlencode($adminFeedback['slug']) ?>">Voir la recette créée</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="flex flex-col items-start justify-between gap-5 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-6 sm:flex-row sm:items-center">
                <div>
                    <h2 class="text-2xl font-bold font-['Playfair_Display']">Recettes</h2>
                    <p class="mt-2 text-on-surface-variant">Ajoutez une recette avec ses ingrédients, ses mesures, ses étapes et ses catégories.</p>
                </div>
                <a class="inline-flex shrink-0 items-center gap-2 rounded bg-[#2F1B14] px-5 py-3 font-semibold text-white shadow-sm transition hover:bg-secondary" href="./?pages=admin&amp;action=new-recipe">
                    <span aria-hidden="true">＋</span> Nouvelle recette
                </a>
            </div>

            <section class="space-y-4" aria-labelledby="admin-recipe-list-heading">
                <div class="flex flex-wrap items-end justify-between gap-3 border-b border-[#C9A45C] pb-2">
                    <div>
                        <h2 class="text-2xl font-bold font-['Playfair_Display']" id="admin-recipe-list-heading">Recettes enregistrées</h2>
                        <p class="mt-1 text-sm text-on-surface-variant"><?= count($adminRecipes) ?> recette<?= count($adminRecipes) === 1 ? '' : 's' ?> dans la base.</p>
                    </div>
                </div>

                <?php if ($adminRecipes === []): ?>
                    <p class="rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-5 text-on-surface-variant">Aucune recette n’est enregistrée pour le moment.</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($adminRecipes as $adminRecipe): ?>
                            <article class="flex flex-col gap-4 rounded-lg border border-[#E5C98A] bg-[#FBF5E6] p-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold font-['Playfair_Display']"><?= $escapeAdminDashboard($adminRecipe->getRecetteTitle()) ?></h3>
                                    <p class="text-sm text-on-surface-variant">
                                        <?= $escapeAdminDashboard($adminRecipe->getRecetteDifficulty()) ?>
                                        · <?= (int) ($adminRecipe->getRecettePortions() ?? 1) ?> portion<?= (int) ($adminRecipe->getRecettePortions() ?? 1) === 1 ? '' : 's' ?>
                                    </p>
                                    <?php if (is_string($adminRecipe->getRecetteSlug()) && $adminRecipe->getRecetteSlug() !== ''): ?>
                                        <a class="mt-1 inline-block text-sm font-semibold text-secondary hover:underline" href="./?pages=recipe&amp;slug=<?= rawurlencode($adminRecipe->getRecetteSlug()) ?>">Voir la recette</a>
                                    <?php endif; ?>
                                </div>
                                <form action="./?pages=admin" class="shrink-0" data-delete-recipe-form data-recipe-title="<?= $escapeAdminDashboard($adminRecipe->getRecetteTitle()) ?>" method="post">
                                    <input name="csrf_token" type="hidden" value="<?= $escapeAdminDashboard($recipeFormCsrfToken) ?>">
                                    <input name="admin_action" type="hidden" value="delete_recipe">
                                    <input name="recipe_id" type="hidden" value="<?= (int) $adminRecipe->getRecetteId() ?>">
                                    <button class="rounded border border-red-700 px-4 py-2 font-semibold text-red-800 transition hover:bg-red-700 hover:text-white" type="submit">Supprimer</button>
                                </form>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <a class="inline-flex font-semibold text-secondary hover:underline" href="./?pages=recettes">Consulter le catalogue de recettes</a>
        </div>
    </section>
</main>
<?php require BASE_URL . '/view/publicView/inc/footer.php'; ?>
<script src="./js/scripts.js?v=<?= filemtime(BASE_URL . '/public/js/scripts.js') ?>"></script>
<script>
// Demande confirmation avant le POST irréversible de suppression d'une recette.
document.querySelectorAll('[data-delete-recipe-form]').forEach(function (form) {
    form.addEventListener('submit', function (event) {
        const title = form.dataset.recipeTitle || 'cette recette';
        const confirmed = window.confirm(`Supprimer définitivement « ${title} » ainsi que ses commentaires, notes, étapes et associations d’ingrédients/catégories ?`);
        if (!confirmed) event.preventDefault();
    });
});
</script>
</body>
</html>
