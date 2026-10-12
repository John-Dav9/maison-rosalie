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
$recipeImage = $recipeImagePath($recipe->getRecetteImage(), 'recettes');
$categories = $recipe->getCategories() ?? [];
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

             <!-- ===========Note : 4 étoiles rouges sur 5 ================-->
             <p class="text-red-600 text-lg mt-2">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star text-gray-300"></i>
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
                   <span class="font-bold font-['Playfair_Display']"><span id="portions">1</span> Pers</span>
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
             <p class="text-sm">ajusté pour <b><span id="portions-texte">1</span> <span id="portions-label">personne</span></b></p>
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
                      <p class="text-xs text-[#B8860B] mt-1 quantite" data-quantite="<?= $escapeRecipeValue($ingredient['quantite']) ?>" data-portions-base="1"><?= $escapeRecipeValue($ingredient['quantite']) ?></p>
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

       <!-- En-tête : titre, phrase, bouton -->
       <div class="text-center">
          <h2 class="text-5xl font-bold font-['Playfair_Display']">
             Commentaires et critiques<span class="text-[#B8860B] text-sm">◆</span>
          </h2>
          <p class="text-sm mt-2">Vos commentaire et réaction sont toujours les bienvenus.</p>
          <button class="w-96 bg-[#2F1B14] text-white text-sm py-2 mt-6 font-['Playfair_Display']">
             <i class="fa-solid fa-feather-pointed text-xs mr-2"></i> Écrivez un commentaire
          </button>
       </div>

       <!-- Commentaire 1 : photo + nom + email + étoiles + texte -->
       <div class="flex gap-4 bg-[#FBF5E6] border border-[#E5C98A] p-4 min-h-40 mt-6">
          <!--- ===== Icon User =====-------->
          <div class="w-16 h-16 rounded-full flex items-center justify-center">
             <i class="fa-regular fa-user text-3xl text-[#2F1B14]"></i>
          </div>

          <div class="flex-1">
             <div class="flex justify-between">
                <div>
                   <p class="font-bold font-['Playfair_Display'] text-sm">Darrell Steward</p>
                   <p class="text-sm mt-1">Darrell.Steward@cf2000.com</p>
                </div>

             </div>
             <p class="text-xs mt-3">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Vero dolore autem similique minima eveniet, fugit sapiente necessitatibus cum consectetur laborum, deserunt
                quod temporibus perspiciatis nam odit vitae quia eaque harum!
                lorem ipsum dolor sit amet consectetur adipisicing elit. Vero dolore autem similique minima eveniet, fugit sapiente necessitatibus cum consectetur laborum, deserunt
                quod temporibus perspiciatis nam odit vitae quia eaque harum!
             </p>
          </div>
       </div>

       <!-- Commentaire 2 -->
       <div class="flex gap-4 bg-[#FBF5E6] border border-[#E5C98A] p-4 min-h-40 mt-6">
          <!--- ===== Icon User =====-------->
          <div class="w-16 h-16 rounded-full flex items-center justify-center">
             <i class="fa-regular fa-user text-3xl text-[#2F1B14]"></i>
          </div>
          <div class="flex-1">
             <div class="flex justify-between">
                <div>
                   <p class="font-bold font-['Playfair_Display'] text-sm">Annette Black</p>
                   <p class="text-sm mt-1">Annette.Black@cf2000.com</p>
                </div>

             </div>
             <p class="text-xs mt-3">
                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                Vero dolore autem similique minima eveniet, fugit sapiente necessitatibus cum consectetur laborum, deserunt
                quod temporibus perspiciatis nam odit vitae quia eaque harum!
                lorem ipsum dolor sit amet consectetur adipisicing elit. Vero dolore autem similique minima eveniet, fugit sapiente necessitatibus cum consectetur laborum, deserunt
                quod temporibus perspiciatis nam odit vitae quia eaque harum!
               </p>
            </div>
         </div>
         <!-- ================= FORMULAIRE : Note + Commentaire + Envoyer ================= -->
         <div class="bg-[#FBF5E6] border border-[#E5C98A] px-8 py-6 mt-6">


          <!-- Commentaire : grande case pour écrire -->
          <div class="mt-4">
             <p class="text-sm font-bold mb-1">Commentaire</p>
             <textarea class="w-full h-20 resize-none bg-white border border-[#E5C98A] p-2"></textarea>
          </div>


          <!-- Bouton Envoyer, à droite -->
          <div class="text-right mt-2">
             <button class="bg-[#2F1B14] text-white text-lg px-6 py-1 font-['Playfair_Display'] rounded-[5px] cursor-pointer
              shadow-[0_6px_0_#1A0F0B,0_5px_15px_-4px_#000] transition-all duration-200 ease-in-out
              hover:opacity-90 focus:opacity-90 focus:outline-none active:shadow-none active:translate-y-[6px]">Envoyer
             </button>
          </div>
       </div>
       <div class="grid grid-cols-2 gap-x-16">


          <!-- Note : 5 étoiles + texte + flèche (colonne de droite) -->
          <div class=" bg-[#FBF5E6] border border-[#E5C98A] p-4 min-h-40 mt-6">

             <div class="flex-1 text-center">
                <p class="text-sm font-bold mb-2">Note</p>
                <select class="w-1/2 -translate-x-6 bg-white border border-[#E5C98A] px-3 py-2 text-sm">
                   <option>★★★★★ (5/5) excellent</option>
                   <option>★★★★☆ (4/5) très bon</option>
                   <option>★★★☆☆ (3/5) bon</option>
                   <option>★★☆☆☆ (2/5) moyen</option>
                   <option>★☆☆☆☆ (1/5) mauvais</option>
                </select>
             </div>
             <div class="text-right mt-6">
                <button class="bg-[#2F1B14] text-white text-lg px-6 py-1 font-['Playfair_Display'] rounded-[5px] cursor-pointer
              shadow-[0_6px_0_#1A0F0B,0_5px_15px_-4px_#000] transition-all duration-200 ease-in-out
              hover:opacity-90 focus:opacity-90 focus:outline-none active:shadow-none active:translate-y-[6px]">Envoyer
                </button>
             </div>
          </div>
       </div>

    </article>
 </section>
 <?php require_once "./../view/publicView/inc/footer.php" ?>
 <script src="./js/scripts.js?v=<?= filemtime(__DIR__ . '/../../public/js/scripts.js') ?>"></script>
 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


 </body>

 </html>
