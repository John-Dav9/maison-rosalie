
<?php
require_once "./../view/publicView/inc/menuPublicView.php";
$escapeRecipeCard = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
$recipeCardImagePath = static function (?string $image): string {
    if ($image === null || trim($image) === '') {
        return '';
    }
    if (preg_match('~^https?://~i', $image)) {
        return $image;
    }
    $image = ltrim($image, '/');
    if (str_starts_with($image, './assets/')) {
        return $image;
    }
    if (str_starts_with($image, 'assets/')) {
        return './' . $image;
    }
    return './assets/images/recettes/' . rawurlencode(basename($image));
};
?>
<section class="bg-[#FBF5E6] font-body-md text-on-surface antialiased selection:bg-secondary-container selection:text-on-secondary-container">
    <main class="w-full min-h-[calc(100vh-20rem)]">
        <div class="flex flex-col w-full">
            <section class="w-full bg-[#2F1B14] text-surface pt-25 pb-space-xl shadow-sm">
                <div class="max-w-[1280px] mx-auto px-margin">
                    <div class="flex flex-col items-center text-center max-w-2xl mx-auto space-y-space-xs">
                        <div class="flex items-center gap-space-xs text-[#fed488] mb-1"><span class="material-symbols-outlined text-[14px]">diamond</span><span class="font-label-sm text-label-sm uppercase tracking-[0.2em] font-semibold text-[#fed488]">Le Carnet Culinaire</span><span class="material-symbols-outlined text-[14px]">diamond</span></div>
                        <h1 class="font-headline-lg text-headline-lg text-surface tracking-tight">Nos Recettes</h1>
                        <p class="font-body-md text-body-md text-surface-variant max-w-lg pt-1">Savoir-faire artisanal &amp; inspirations gourmandes à savourer et partager</p>
                    </div>
                    <div class="mt-space-lg bg-white/10 backdrop-blur-sm p-space-sm rounded-DEFAULT shadow-sm flex flex-col md:flex-row items-center justify-between gap-space-sm">
                        <div class="relative w-full md:w-96 flex items-center"><span class="material-symbols-outlined absolute left-3 text-[#fed488] text-[20px] pointer-events-none">search</span><input aria-label="Rechercher une recette par son titre" class="w-full pl-10 pr-space-md py-2 bg-[#211a18]/95 text-surface font-body-sm text-body-sm rounded-DEFAULT focus:outline-none placeholder:text-outline-variant shadow-sm" id="recipeSearch" placeholder="Rechercher une recette..." type="text"></div>
                        <div class="flex items-center gap-space-sm w-full md:w-auto justify-end flex-wrap">
                            <div class="relative inline-flex items-center"><select aria-label="Filtrer par catégorie" class="appearance-none bg-[#211a18]/95 text-surface font-body-sm text-body-sm pl-3 pr-8 py-2 rounded-DEFAULT shadow-sm cursor-pointer focus:outline-none" id="recipeCategoryFilter">
                                    <option value="" selected="">Catégorie : Toutes les catégories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $escapeRecipeCard($category->getId()) ?>"><?= $escapeRecipeCard($category->getTitle()) ?></option>
                                    <?php endforeach; ?>
                                </select><span class="material-symbols-outlined absolute right-2 text-[#fed488] text-[18px] pointer-events-none">expand_more</span></div>
                            <div class="relative inline-flex items-center"><select aria-label="Filtrer par niveau" class="appearance-none bg-[#211a18]/95 text-surface font-body-sm text-body-sm pl-3 pr-8 py-2 rounded-DEFAULT shadow-sm cursor-pointer focus:outline-none" id="recipeDifficultyFilter">
                                    <option value="" selected="">Niveau : Tous niveaux</option>
                                    <?php foreach ($difficultes as $difficulte): ?>
                                        <option value="<?= $escapeRecipeCard($difficulte) ?>"><?= $escapeRecipeCard($difficulte) ?></option>
                                    <?php endforeach; ?>
                                </select><span class="material-symbols-outlined absolute right-2 text-[#fed488] text-[18px] pointer-events-none">expand_more</span></div>
                            <div class="relative inline-flex items-center"><select aria-label="Trier les recettes" class="appearance-none bg-[#211a18]/95 text-surface font-body-sm text-body-sm pl-3 pr-8 py-2 rounded-DEFAULT shadow-sm cursor-pointer focus:outline-none" id="recipeSort">
                                    <option value="recent" selected="" class="bg-[#211a18] text-surface">Trier par : Plus récents</option>
                                    <option value="oldest" class="bg-[#211a18] text-surface">Plus anciens</option>
                                    <option value="popular" class="bg-[#211a18] text-surface">Plus populaires</option>
                                    <option value="preparation" class="bg-[#211a18] text-surface">Temps de préparation</option>
                                    <option value="rating" class="bg-[#211a18] text-surface">Mieux notées</option>
                                </select><span class="material-symbols-outlined absolute right-2 text-[#fed488] text-[18px] pointer-events-none">expand_more</span></div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Recipe Grid: 4 Columns desktop -->
            <section class="w-full max-w-[1280px] mx-auto px-margin py-space-sm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-gutter" id="recipeGrid">
                    <?php if ($recettes === []): ?>
                        <p class="col-span-full text-center py-12 text-on-surface-variant">Aucune recette n’est encore enregistrée.</p>
                    <?php else: ?>
                        <?php foreach ($recettes as $recette): ?>
                            <?php
                            $recipeTitle = $recette->getRecetteTitle() ?? 'Recette';
                            $recipeSlug = $recette->getRecetteSlug() ?? '';
                            $recipeImage = $recipeCardImagePath($recette->getRecetteImage());
                            $recipeCategories = $recette->getCategories() ?? [];
                            $categoryNames = array_map(static fn($category): string => $category->getTitle() ?? '', $recipeCategories);
                            $categoryIds = array_values(array_filter(array_map(static fn($category): ?int => $category->getId(), $recipeCategories), static fn($id): bool => $id !== null));
                            $categoryLabel = $categoryNames !== [] ? implode(' · ', $categoryNames) : 'Non classée';
                            $recipeId = (int) $recette->getRecetteId();
                            $recipeRating = $recipeRatingStats[$recipeId] ?? ['count' => 0, 'average' => null];
                            $recipeRatingCount = (int) $recipeRating['count'];
                            $recipeRatingAverage = $recipeRating['average'];
                            $recipeRatingValue = $recipeRatingAverage === null
                                ? 0.0
                                : max(0.0, min(5.0, (float) $recipeRatingAverage));
                            $recipePrepText = $recette->getRecetteDurationPrep() ?? '';
                            $recipeCreatedAt = strtotime($recette->getRecetteDateCreate() ?? '') ?: 0;
                            ?>
                            <article class="group bg-surface-container-lowest rounded-DEFAULT overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between" data-recipe-card data-recipe-title="<?= $escapeRecipeCard($recipeTitle) ?>" data-category-ids="<?= $escapeRecipeCard(implode(',', $categoryIds)) ?>" data-difficulty="<?= $escapeRecipeCard($recette->getRecetteDifficulty()) ?>" data-created-at="<?= $recipeCreatedAt ?>" data-preparation-time="<?= $escapeRecipeCard($recipePrepText) ?>" data-rating-count="<?= (int) $recipeRating['count'] ?>" data-rating-average="<?= $recipeRating['average'] === null ? '' : number_format((float) $recipeRating['average'], 6, '.', '') ?>" style="border: 1.5px solid rgb(139, 94, 60);">
                                <div class="flex flex-col">
                                    <div class="relative m-2 overflow-hidden rounded-DEFAULT bg-surface-container aspect-square">
                                        <!-- Garder l’image même si la base ne contient pas encore de chemin. -->
                                        <img alt="<?= $escapeRecipeCard($recipeTitle) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" src="<?= $escapeRecipeCard($recipeImage) ?>">
                                        <?php if ($categoryLabel !== 'Non classée'): ?>
                                            <div class="absolute top-2 left-2 flex items-center gap-1.5">
                                                <span class="px-2 py-0.5 bg-surface/90 backdrop-blur-sm text-on-surface font-label-sm text-label-sm uppercase tracking-wider rounded-DEFAULT shadow-sm"><?= $escapeRecipeCard($categoryLabel) ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <button aria-label="Aperçu rapide de <?= $escapeRecipeCard($recipeTitle) ?>" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-surface/85 backdrop-blur-sm text-on-surface hover:bg-surface hover:text-secondary flex items-center justify-center transition-colors shadow-sm" type="button">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </button>
                                    </div>
                                    <div class="px-4 pt-2 pb-1 flex flex-col">
                                        <div class="flex items-center justify-between text-outline text-body-sm mb-1">
                                            <span class="font-label-sm text-label-sm uppercase tracking-wider text-secondary font-semibold"><?= $escapeRecipeCard($categoryLabel) ?></span>
                                            <span class="font-label-sm text-label-sm uppercase tracking-wider text-secondary font-semibold"><?= $escapeRecipeCard($recette->getRecetteDifficulty()) ?></span>
                                        </div>
                                        <h3 class="font-headline-sm text-headline-sm text-on-surface group-hover:text-secondary transition-colors line-clamp-1 mb-1">
                                            <?= $escapeRecipeCard($recipeTitle) ?>
                                        </h3>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2 leading-snug mb-3">
                                            <?= $escapeRecipeCard($recette->getRecetteDescription()) ?>
                                        </p>
                                        <!-- Même remplissage proportionnel que sur la fiche recette; zéro vote = cinq étoiles vides. -->
                                        <p class="font-label-sm text-label-sm text-secondary mb-3 inline-flex items-center gap-0.5 text-lg" aria-label="<?= $recipeRatingCount > 0 ? 'Moyenne de ' . number_format((float) $recipeRatingAverage, 1, ',', '') . ' sur 5, ' . $recipeRatingCount . ' ' . ($recipeRatingCount > 1 ? 'notes' : 'note') : 'Aucune note' ?>">
                                            <?php for ($starIndex = 1; $starIndex <= 5; $starIndex++): ?>
                                                <?php $starFill = max(0, min(100, ($recipeRatingValue - ($starIndex - 1)) * 100)); ?>
                                                <span class="relative inline-block leading-none text-[#8B5E3C]" aria-hidden="true">
                                                    ☆
                                                    <span class="absolute inset-y-0 left-0 overflow-hidden whitespace-nowrap text-[#C9A45C]" style="width: <?= $starFill ?>%">★</span>
                                                </span>
                                            <?php endfor; ?>
                                            <?php if ($recipeRatingCount > 0): ?>
                                                <span class="ml-1 text-xs text-on-surface-variant">(<?= $recipeRatingCount ?> <?= $recipeRatingCount > 1 ? 'notes' : 'note' ?>)</span>
                                            <?php endif; ?>
                                        </p>
                                        <div class="bg-surface-container-low px-2.5 py-1.5 rounded-DEFAULT text-on-surface-variant font-body-sm text-xs flex items-center justify-between gap-2">
                                            <span>⏱ Préparation : <?= $escapeRecipeCard($recette->getRecetteDurationPrep()) ?></span>
                                            <span>Cuisson : <?= $escapeRecipeCard($recette->getRecetteDurationCook()) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 pb-4 pt-3 mt-1 flex items-center justify-between gap-2">
                                    <span class="font-label-sm text-label-sm text-outline tracking-widest uppercase">Recette n°<?= str_pad((string) $recipeId, 2, '0', STR_PAD_LEFT) ?></span>
                                    <a class="px-3 py-1.5 bg-[#2F1B14] text-on-primary hover:bg-secondary transition-colors font-label-sm text-label-sm uppercase tracking-wider rounded-DEFAULT" data-path="<?= $escapeRecipeCard($recipeSlug) ?>" href="./?pages=recipe&amp;slug=<?= rawurlencode($recipeSlug) ?>">
                                        Voir la recette
                                    </a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                        <p class="hidden col-span-full text-center py-12 text-on-surface-variant" hidden id="recipeNoResults" role="status">Aucune recette ne correspond à ces critères.</p>
                    <?php endif; ?>
                </div>
            </section>
            
            <!-- Newsletter & Culinary Note Callout (Editorial Section) -->
            <section class="w-full max-w-[1280px] mx-auto px-margin py-space-xl">
                <div class="relative bg-surface-container rounded-DEFAULT p-space-xl overflow-hidden shadow-sm flex flex-col md:flex-row items-center justify-between gap-space-lg">
                    <div class="max-w-xl space-y-space-xs z-10 text-left">
                        <span class="font-label-sm text-label-sm uppercase tracking-widest text-secondary font-semibold">Conseil de l'Atelier</span>
                        <h3 class="font-headline-md text-headline-md text-on-surface">La Température Parfaite du Chocolat</h3>
                        <p class="font-body-md text-body-md text-on-surface-variant">
                            Pour une brillance miroir et une cassure nette, respectez scrupuleusement la courbe de cristallisation : fonte à 50°C, descente à 27°C, puis remontée précise à 31°C avant moulage.
                        </p>
                    </div>
                    <div class="z-10 shrink-0">
                        <a class="px-space-lg py-space-sm bg-primary text-on-primary hover:bg-secondary font-label-md text-label-md uppercase tracking-wider rounded-DEFAULT transition-colors inline-block shadow-sm" data-path="la-maison" href="#">
                            Découvrir les fiches techniques
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <?php require_once "./../view/publicView/inc/footer.php" ?>
    <script src="./js/scripts.js"></script>
</section>
</body>

</html>