<?php require_once "./../view/publicView/inc/header.php" ?>

    <!-- Les recettes réelles et leurs notes sont préparées par le contrôleur public. -->
    <?php $topRecipes = is_array($topRecipes ?? null) ? $topRecipes : []; ?>

    <section id="top-recipes" class="top-recipes" aria-labelledby="top-recipes-title">
        <div class="top-recipes__inner">

            <!-- En-tête de section -->
            <div class="top-recipes__heading">
                <span class="top-recipes__eyebrow">Sélection Signature</span>
                <h2 id="top-recipes-title" class="top-recipes__title">Notre Top 3 des recettes</h2>
                <div class="top-recipes__ornament" aria-hidden="true">
                    <span class="top-recipes__ornament-line"></span>
                    <i class="fa-solid fa-cookie-bite"></i>
                    <span class="top-recipes__ornament-line"></span>
                </div>
                <p class="top-recipes__subtitle">Classement calculé à partir des notes laissées par la communauté.</p>
            </div>

            <?php if (empty($topRecipes)): ?>
                <!-- État "aucune recette notée" (FE-12) -->
                <p class="top-recipes__empty">
                    Les recettes les mieux notées apparaîtront ici dès que les premiers avis seront déposés.
                </p>
            <?php else: ?>
                <!-- Grille : 3 colonnes ordinateur, 1 colonne mobile (FE-11) -->
                <div class="top-recipes__grid">
                    <?php foreach ($topRecipes as $recipe): ?>
                        <!-- data-reveal : ciblé par scripts.js pour l'animation d'apparition au scroll -->
                        <article class="recipe-card" data-reveal>

                            <!-- Visuel + badge de rang -->
                            <div class="recipe-card__media">
                                <img class="recipe-card__image"
                                     src="<?= htmlspecialchars($recipe['image']) ?>"
                                     alt="<?= htmlspecialchars($recipe['title']) ?>"
                                     loading="lazy">
                                <span class="recipe-card__badge">
                                    <?= htmlspecialchars($recipe['rank']) ?> &bull; <?= htmlspecialchars($recipe['badge']) ?>
                                </span>
                            </div>

                            <!-- Contenu -->
                            <div class="recipe-card__body">
                                <div>
                                    <p class="recipe-card__meta">
                                        <i class="fa-regular fa-clock" aria-hidden="true"></i>
                                        <?= htmlspecialchars((string) $recipe['time'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> &bull; <?= htmlspecialchars((string) $recipe['level'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                                    </p>
                                    <h3 class="recipe-card__title"><?= htmlspecialchars($recipe['title']) ?></h3>
                                    <p class="recipe-card__desc"><?= htmlspecialchars($recipe['desc']) ?></p>
                                </div>

                                <a class="recipe-card__btn"
                                   href="./?pages=recipe&amp;slug=<?= urlencode($recipe['slug']) ?>">
                                    Voir la recette
                                </a>
                            </div>

                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>

    <?php require_once "./../view/publicView/inc/footer.php" ?>
    <script src="./js/scripts.js"></script>
</body>

</html>