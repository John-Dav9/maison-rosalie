 <?php
// Les données de recette sont chargées par le contrôleur à partir du slug demandé.
$escapeRecipeValue = static function ($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
$recipeImagePath = static function (?string $image, string $folder): string {
    if ($image === null || trim($image) === '') {
        return '';
    }
    if (preg_match('~^https?://~i', $image)) {
        return $image;
    }
    return './assets/images/' . trim($folder, '/') . '/' . rawurlencode(basename($image));
};
$recipeTitle = $recipe->getRecetteTitle() ?? 'Recette';
$recipePortions = max(1, (int) ($recipe->getRecettePortions() ?? 1));
$recipeImage = $recipeImagePath($recipe->getRecetteImage(), 'recettes');
$categories = $recipe->getCategories() ?? [];
$recipeRatingCount = (int) ($recipeRatingSummary['count'] ?? 0);
$recipeRatingAverage = $recipeRatingSummary['average'] ?? null;
$recipeRatingValue = $recipeRatingAverage === null
    ? 0.0
    : max(0.0, min(5.0, (float) $recipeRatingAverage));
$recipeActionUrl = './?pages=recipe&slug=' . rawurlencode((string) $recipeSlug);
require_once "./../view/publicView/inc/menuPublicView.php";
?>
 <!--=========Fond créme pour toute la section ================-->
 <section id="recettes" class="bg-[#FBF5E6] text-[#2F1B14] pb-16">

    <!-- ===  ===========  En-tete de la page recette  ===-->
    <div class="text-center py-20">
       <a href="#" class="text-[#B8860B] underline font-['Playfair_Display']">L'atelier Maison Rosalie</a>
       <h2 class="text-5xl font-bold font-['Playfair_Display'] mt-2">
          <span class="text-[#C9A45C] text-xl">◆</span> <?= $escapeRecipeValue($recipeTitle) ?>
       </h2>
       <p class="text-sm mt-4">
          <?= nl2br($escapeRecipeValue($recipe->getRecetteDescription())) ?>
       </p>
    </div>

    <!--  === ==========Boutons des catégories ========= -->
    <!--============= Le bouton foncé = la catégorie choisie ============-->
    <div class="flex justify-center gap-5 mb-10">
       <?php foreach ($categories as $category): ?>
          <span class="categorie bg-[#F8EDD3] px-8 py-1 font-['Playfair_Display']"><?= $escapeRecipeValue($category->getTitle()) ?></span>
       <?php endforeach; ?>
    </div>

    <!-- ================= CARTE DE LA RECETTE ================= -->
    <article class="max-w-6xl mx-auto bg-white border border-[#C9A45C] rounded-lg">

       <!-- -----============== Partie du haut : image + infos =============----- -->
       <div class="grid grid-cols-2 gap-8 p-6">

          <!--================Image de la recette ================-->
          <img src="<?= $escapeRecipeValue($recipeImage) ?>" alt="<?= $escapeRecipeValue($recipeTitle) ?>" class="w-full h-72 object-cover border border-[#C9A45C] mt-8">

          <!-- ==============Infos de la recette ============-->
          <div>
             <!--============= Étiquette Moyen, en haut à droite============== -->
             <p class="text-right">
                <span class="bg-[#F8EDD3] px-6 py-2 font-bold font-['Playfair_Display']"><?= $escapeRecipeValue($recipe->getRecetteDifficulty()) ?></span>
             </p>

             <!-- ==============Titre ==============-->
             <h3 class="text-3xl font-bold font-['Playfair_Display'] mt-4"><?= $escapeRecipeValue($recipeTitle) ?></h3>

             <!-- Description issue de la recette sélectionnée. -->
             <p class="text-xs mt-2"><?= nl2br($escapeRecipeValue($recipe->getRecetteDescription())) ?></p>

             <!-- Remplit chaque étoile selon la moyenne; sans vote, les cinq restent vides. -->
             <p class="inline-flex items-center gap-0.5 text-2xl mt-2" aria-label="<?= $recipeRatingCount > 0 ? 'Moyenne de ' . number_format((float) $recipeRatingAverage, 1, ',', '') . ' sur 5, ' . $recipeRatingCount . ' ' . ($recipeRatingCount > 1 ? 'notes' : 'note') : 'Aucune note' ?>">
                <?php for ($starIndex = 1; $starIndex <= 5; $starIndex++): ?>
                   <?php $starFill = max(0, min(100, ($recipeRatingValue - ($starIndex - 1)) * 100)); ?>
                   <span class="relative inline-block leading-none text-[#8B5E3C]" aria-hidden="true">
                      ☆
                      <span class="absolute inset-y-0 left-0 overflow-hidden whitespace-nowrap text-[#C9A45C]" style="width: <?= $starFill ?>%">★</span>
                   </span>
                <?php endfor; ?>
                <?php if ($recipeRatingCount > 0): ?>
                   <span class="ml-2 text-sm text-[#2F1B14]">(<?= $recipeRatingCount ?> <?= $recipeRatingCount > 1 ? 'notes' : 'note' ?>)</span>
                <?php endif; ?>
             </p>

             <!-- ==============Les 3 cases : temps de préparation, temps total, difficulté ==================-->
             <div class="grid grid-cols-3 gap-3 mt-4 text-center">
                <div class="border border-[#C9A45C] py-3">
                   <p class="text-xs">TEMPS PREP</p>
                   <p class="font-bold font-['Playfair_Display']"><?= $escapeRecipeValue($recipe->getRecetteDurationPrep()) ?></p>
                </div>
                <div class="border border-[#C9A45C] py-3">
                   <p class="text-xs">CUISSON</p>
                   <p class="font-bold font-['Playfair_Display']"><?= $escapeRecipeValue($recipe->getRecetteDurationCook()) ?></p>
                </div>
                <div class="border border-[#C9A45C] py-3">
                   <p class="text-xs">DIFFICULTÉ</p>
                   <p class="font-bold font-['Playfair_Display']"><?= $escapeRecipeValue($recipe->getRecetteDifficulty()) ?></p>
                </div>
             </div>

             <!-- ====== Portions : boutons =========- et + -->
             <div class="flex justify-between items-center bg-[#F8EDD3] border border-[#C9A45C] p-4 mt-6">
                <div>
                   <p class="text-xs font-bold">RENDEMENT DE LA RECETTE / PORTIONS</p>
                   <p class="text-xs mt-2">Cliquez pour changer les portions</p>
                </div>
                <div class="flex items-center gap-2 bg-white border border-[#C9A45C] rounded-full px-2 py-1">
                   <button id="moins" class="bg-[#F8EDD3] px-2">−</button>
                   <span class="font-bold font-['Playfair_Display']"><span id="portions"><?= $recipePortions ?></span> Pers</span>
                   <button id="plus" class="bg-[#F8EDD3] px-2">+</button>
                </div>
             </div>
          </div>
       </div>
       <!-- =========Les Ingredients =======---->
       <section class="border-t border-[#C9A45C] p-6">
          <!------ ====== Titre + ajusté pour x personnes ============  --->
          <div class="flex justify-between items-center mb-4">
             <h3 class="text-3xl font-bold font-['Playfair_Display']">
                <i class="fa-solid fa-basket-shopping text-[#C9A45C] text-xl"></i>Les ingrédients
             </h3>
             <p class="text-sm">ajusté pour <b><span id="portions-texte"><?= $recipePortions ?></span> <span id="portions-label"><?= $recipePortions === 1 ? 'personne' : 'personnes' ?></span></b></p>
          </div>

          <!-- Carrousel : les cartes défilent de gauche à droite -->
          <div id="carrousel" class=" flex gap-4 overflow-x-auto scroll-smooth">

             <!-- Les ingrédients, quantités et visuels proviennent de la recette choisie. -->
             <?php foreach ($recipeIngredients as $ingredient): ?>
                <div class="flex-1 min-w-48 bg-[#FBF5E6] border border-[#C9A45C] rounded-xl p-2 text-center">
                   <div class="overflow-hidden rounded">
                      <?php $ingredientImage = $recipeImagePath($ingredient['image'] ?? null, 'ingredients'); ?>
                      <!-- Laisser le chemin DB tel quel (même vide) : le navigateur affichera le texte alt si l'image échoue. -->
                      <img src="<?= $escapeRecipeValue($ingredientImage) ?>" alt="<?= $escapeRecipeValue($ingredient['name'] ?? '') ?>" class="h-20 w-full object-cover rounded transition duration-300 hover:scale-110">
                   </div>
                   <p class="font-bold font-['Playfair_Display'] text-sm mt-3"><?= $escapeRecipeValue($ingredient['name'] ?? '') ?></p>
                   <?php if (!empty($ingredient['quantite'])): ?>
                      <p class="text-xs text-[#B8860B] mt-1 quantite" data-quantite="<?= $escapeRecipeValue($ingredient['quantite']) ?>" data-portions-base="<?= $recipePortions ?>" data-unit="<?= $escapeRecipeValue($ingredient['unit'] ?? '') ?>">
                         <?= $escapeRecipeValue($ingredient['quantite']) ?><?= !empty($ingredient['unit']) ? ' ' . $escapeRecipeValue($ingredient['unit']) : '' ?>
                      </p>
                   <?php endif; ?>
                </div>
             <?php endforeach; ?>

          </div>
          <!---=======Fleches du carrousel ===== ----->
          <div class="flex justify-between mt-3">
             <button id="gauche" class="bg-[#2F1B14] text-[#C9A45C] w-7 h-7 rounded-full">
                <i class="fa-solid fa-chevron-left text-xs"></i>
             </button>
             <button id="droite" class="bg-[#2F1B14] text-[#C9A45C] w-7 h-7 rounded-full">
                <i class="fa-solid fa-chevron-right text-xs"></i>
             </button>
          </div>
       </section>

       <!---=========Les étapes de la recette ==========-->
       <section class="border-t border-[#C9A45C] p-6">
          <h3 class="text-3xl font-bold font-['Playfair_Display'] mb-6">
             <i class="fa-solid fa-list-ol text-[#C9A45C] text"></i> Les étapes
          </h3>
          <!-- Les étapes affichées sont celles de cette recette, dans l'ordre du manager. -->
          <div class="space-y-4">
             <?php if ($recipeSteps === []): ?>
                <p>Les étapes de cette recette ne sont pas encore renseignées.</p>
             <?php else: ?>
                <?php foreach ($recipeSteps as $step): ?>
                   <label class="etape relative flex gap-3 bg-[#FBF5E6] border border-[#E5C98A] p-3 cursor-pointer">
                      <input type="checkbox" class="mt-1">
                      <div>
                         <p class="text-sm text-[#B8860B]">ÉTAPE <?= (int) $step->getNbStep() ?></p>
                         <?php if ($step->getTitle()): ?><p class="font-bold"><?= $escapeRecipeValue($step->getTitle()) ?></p><?php endif; ?>
                         <p><?= nl2br($escapeRecipeValue($step->getDescription())) ?></p>
                         <?php $stepImage = $recipeImagePath($step->getImage(), 'etapes'); ?>
                         <?php if ($stepImage !== ''): ?><img src="<?= $escapeRecipeValue($stepImage) ?>" alt="Illustration de l'étape <?= (int) $step->getNbStep() ?>" class="mt-3 max-h-64 object-cover"><?php endif; ?>
                      </div>
                   </label>
                <?php endforeach; ?>
             <?php endif; ?>
          </div>
       </section>
    </article>
 </section>

 <!-- ================= SECTION COMMENTAIRES ================= -->
 <section id="commentaires" class="bg-[#FBF5E6] text-[#2F1B14] pb-16">
    <article class="max-w-6xl mx-auto bg-white border border-[#C9A45C] rounded-lg px-12 py-6">

       <!-- En-tête des retours réellement enregistrés pour cette recette. -->
       <div class="text-center">
          <h2 class="text-5xl font-bold font-['Playfair_Display']">
             Commentaires et critiques<span class="text-[#B8860B] text-sm">◆</span>
          </h2>
          <p class="text-sm mt-2">Vos commentaires et réactions sont toujours les bienvenus.</p>
       </div>

       <?php if (is_array($recipeActionFeedback) && isset($recipeActionFeedback['message'])): ?>
          <p class="mt-4 text-center <?= ($recipeActionFeedback['type'] ?? '') === 'success' ? 'text-green-700' : 'text-red-700' ?>" role="status">
             <?= $escapeRecipeValue($recipeActionFeedback['message']) ?>
          </p>
       <?php endif; ?>

       <div class="mt-6 space-y-4">
          <?php if (empty($comments)): ?>
             <p class="bg-[#FBF5E6] border border-[#E5C98A] p-4 text-center">Aucun commentaire pour le moment.</p>
          <?php else: ?>
             <?php foreach ($comments as $comment): ?>
                <article class="flex gap-4 bg-[#FBF5E6] border border-[#E5C98A] p-4 rounded-md">
                   <div class="w-12 h-12 shrink-0 rounded-full flex items-center justify-center" aria-hidden="true">
                      <i class="fa-regular fa-user text-2xl text-[#2F1B14]"></i>
                   </div>
                   <div class="flex-1 min-w-0">
                      <div class="flex flex-wrap items-center justify-between gap-2">
                         <p class="font-bold font-['Playfair_Display'] text-sm"><?= $escapeRecipeValue($comment->getAuthor()) ?></p>
                         <time class="text-xs" datetime="<?= $escapeRecipeValue($comment->getCreatedAt()) ?>">
                            <?= $escapeRecipeValue($comment->getCreatedAt()) ?>
                         </time>
                      </div>
                      <?php if ($comment->getSubject()): ?>
                         <p class="font-semibold mt-2"><?= $escapeRecipeValue($comment->getSubject()) ?></p>
                      <?php endif; ?>
                      <p class="text-sm mt-2 whitespace-pre-line"><?= $escapeRecipeValue($comment->getMessage()) ?></p>

                      <?php if ($currentUserId > 0 && $currentUserId === (int) $comment->getUsersId()): ?>
                         <details class="mt-3">
                            <summary class="cursor-pointer text-sm font-semibold text-[#8B5E3C]">Modifier mon commentaire</summary>
                            <form action="<?= $escapeRecipeValue($recipeActionUrl) ?>" method="post" class="mt-2 space-y-2">
                               <input type="hidden" name="csrf_token" value="<?= $escapeRecipeValue($recipeActionCsrfToken) ?>">
                               <input type="hidden" name="recipe_action" value="comment_update">
                               <input type="hidden" name="comment_id" value="<?= (int) $comment->getId() ?>">
                               <label class="sr-only" for="edit-comment-<?= (int) $comment->getId() ?>">Modifier le commentaire</label>
                               <textarea id="edit-comment-<?= (int) $comment->getId() ?>" class="w-full min-h-20 resize-y bg-white border border-[#E5C98A] p-2" maxlength="300" name="message" required><?= $escapeRecipeValue($comment->getMessage()) ?></textarea>
                               <button class="bg-[#2F1B14] text-white text-sm px-4 py-2 rounded" type="submit">Enregistrer la modification</button>
                            </form>
                         </details>
                         <form action="<?= $escapeRecipeValue($recipeActionUrl) ?>" method="post" class="mt-2">
                            <input type="hidden" name="csrf_token" value="<?= $escapeRecipeValue($recipeActionCsrfToken) ?>">
                            <input type="hidden" name="recipe_action" value="comment_delete">
                            <input type="hidden" name="comment_id" value="<?= (int) $comment->getId() ?>">
                            <button class="text-sm text-red-700 underline" type="submit">Supprimer mon commentaire</button>
                         </form>
                      <?php endif; ?>
                   </div>
                </article>
             <?php endforeach; ?>
          <?php endif; ?>
       </div>
       <!-- Les deux actions sont de vrais formulaires, protégés côté serveur et par jeton CSRF. -->
       <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
          <div class="bg-[#FBF5E6] border border-[#E5C98A] p-6 rounded-md">
             <h3 class="text-lg font-bold font-['Playfair_Display'] mb-3">Laisser un commentaire</h3>
             <?php if ($currentUserId > 0): ?>
                <form action="<?= $escapeRecipeValue($recipeActionUrl) ?>" method="post" class="space-y-3">
                   <input type="hidden" name="csrf_token" value="<?= $escapeRecipeValue($recipeActionCsrfToken) ?>">
                   <input type="hidden" name="recipe_action" value="comment_create">
                   <label class="block text-sm font-semibold" for="new-recipe-comment">Votre commentaire</label>
                   <textarea id="new-recipe-comment" class="w-full min-h-28 resize-y bg-white border border-[#E5C98A] p-2" maxlength="300" name="message" required></textarea>
                   <p class="text-xs">300 caractères maximum.</p>
                   <button class="bg-[#2F1B14] text-white text-sm px-5 py-2 rounded" type="submit">Publier le commentaire</button>
                </form>
             <?php else: ?>
                <p class="text-sm mb-3">Connectez-vous pour publier un commentaire.</p>
                <button class="bg-[#2F1B14] text-white text-sm px-5 py-2 rounded" data-auth-open type="button">Connexion / inscription</button>
             <?php endif; ?>
          </div>

          <div class="bg-[#FBF5E6] border border-[#E5C98A] p-6 rounded-md">
             <h3 class="text-lg font-bold font-['Playfair_Display'] mb-3">Votre note</h3>
             <?php if ($currentUserId > 0): ?>
                <form action="<?= $escapeRecipeValue($recipeActionUrl) ?>" method="post" class="space-y-3">
                   <input type="hidden" name="csrf_token" value="<?= $escapeRecipeValue($recipeActionCsrfToken) ?>">
                   <input type="hidden" name="recipe_action" value="rating_save">
                   <label class="block text-sm font-semibold" for="recipe-rating">Choisissez une note sur 5</label>
                   <select id="recipe-rating" class="w-full bg-white border border-[#E5C98A] px-3 py-2 text-sm" name="rating" required>
                      <option value="" disabled <?= $currentUserRating === null ? 'selected' : '' ?>>Sélectionner une note</option>
                      <?php foreach ([5, 4, 3, 2, 1] as $ratingOption): ?>
                         <option value="<?= $ratingOption ?>" <?= $currentUserRating !== null && $currentUserRating->getNote() === $ratingOption ? 'selected' : '' ?>><?= str_repeat('★', $ratingOption) . str_repeat('☆', 5 - $ratingOption) ?> (<?= $ratingOption ?>/5)</option>
                      <?php endforeach; ?>
                   </select>
                   <?php if ($currentUserRating !== null): ?>
                      <p class="text-xs">Votre note précédente sera remplacée.</p>
                   <?php endif; ?>
                   <button class="bg-[#2F1B14] text-white text-sm px-5 py-2 rounded" type="submit">Enregistrer ma note</button>
                </form>
             <?php else: ?>
                <p class="text-sm mb-3">Connectez-vous pour noter cette recette.</p>
                <button class="bg-[#2F1B14] text-white text-sm px-5 py-2 rounded" data-auth-open type="button">Connexion / inscription</button>
             <?php endif; ?>
          </div>
       </div>

    </article>
 </section>
 <?php require_once "./../view/publicView/inc/footer.php" ?>
 <script src="./js/scripts.js?v=<?= filemtime(__DIR__ . '/../../public/js/scripts.js') ?>"></script>
 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


 </body>

 </html>
