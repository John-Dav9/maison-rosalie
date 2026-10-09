<?php require_once "./../view/publicView/inc/menuPublicView.php" ?>


    <!-- Wrapper global du site : remplace le body pour le ciblage CSS/JS,
       car on évite d'appliquer du style directement sur body ou html. -->
    <div id="app-wrapper">

      <!-- SECTION HÉRO DE LA PAGE D'ACCUEIL
       Structure :
         1. .hero__slides   → les 3 images plein cadre (empilées, fondu enchaîné)
         2. .hero__overlay   → voile sombre pour la lisibilité du texte
         3. .hero__content   → titre + texte d'accroche + bouton "découvrir"
         4. .hero__dots      → 3 points de navigation verticaux (gauche)
         5. .hero__thumbs    → 2 vignettes cliquables (bas droite)
         6. .hero__arrows    → flèches < > (bas centre)

       Le script script.js synchronise slides, dots et vignettes
       sur le même index : quand on clique sur un dot ou une vignette,
       la slide correspondante s'affiche. -->
      <section id="hero">

        <!-- 1. IMAGES DU CAROUSEL -->
        <div class="hero__slides">

          <!-- Slide 1 : couple en terrasse (visible par défaut grâce à .is-active) -->
          <div class="hero__slide is-active">
            <img
              src="./assets/images/degustation-de-chocolat-en-terrasse.webp"
              alt="Couple dégustant du chocolat en terrasse" />
          </div>

          <!-- Slide 2 : chocolatier en atelier -->
          <div class="hero__slide">
            <img
              src="./assets/images/artisan-chocolatier-au-travail.webp"
              alt="Chocolatier au travail dans son atelier" />
          </div>

          <!-- Slide 3 : tablette de chocolat artisanal -->
          <div class="hero__slide">
            <img src="./assets/images/tablettes-de-chocolat-artisanal.webp" alt="Tablette de chocolat artisanal" />
          </div>

          <!-- Slide 4 : fèves de cacao -->
          <div class="hero__slide">
            <img src="./assets/images/emballage-tablette-de-chocolat.webp" alt="Fèves de cacao" />
          </div>

        </div>

        <!-- 2. VOILE SOMBRE -->
        <!-- Dégradé semi-transparent par-dessus l'image pour la lisibilité du texte -->
        <div class="hero__overlay"></div>

        <!-- 3. BLOC TEXTE -->
        <div class="hero__content">

          <!-- Titre principal + trait doré décoratif en-dessous -->
          <div>
            <h1 class="hero__title">Maison Rosalie</h1>
            <span class="hero__divider"></span>
          </div>

          <!-- Texte d'accroche (placeholder hipster ipsum, à remplacer par le vrai contenu) -->
          <p class="hero__text">
            L'art du chocolat belge, sublimé par des fèves d'exception et un savoir-faire artisanal.
            Une invitation à la gourmandise, au cœur de Bruxelles.
          </p>

          <!-- Bouton d'appel à l'action -->
          <a href="./?pages=about" class="hero__cta">Découvrir</a>

        </div>

        <!-- 4. DOTS VERTICAUX (gauche) -->
        <!-- 4 petits cercles, un par slide. Celui avec .is-active est doré. -->
        <div class="hero__dots">
          <button type="button" class="hero__dot is-active" aria-label="Slide 1"></button>
          <button type="button" class="hero__dot" aria-label="Slide 2"></button>
          <button type="button" class="hero__dot" aria-label="Slide 3"></button>
          <button type="button" class="hero__dot" aria-label="Slide 4"></button>
        </div>

        <!-- 5. VIGNETTES (bas droite)
         2 miniatures qui prévisualisent les AUTRES slides (pas la slide active).
         Chaque vignette porte un attribut data-slide="X" qui indique vers quelle
         slide elle doit naviguer au clic (le JS lit ce data-slide au lieu de
         se fier à l'index dans le forEach, ce qui permet de montrer n'importe
         quelle image sans se soucier de l'ordre).
           - hero__thumb--lg : grande vignette → slide 2 (tablette de chocolat)
           - hero__thumb--sm : petite vignette → slide 1 (chocolatier), coupée par le bord droit
         La vignette dont le data-slide correspond à la slide active reçoit .is-active
         (bordure dorée). Masquées en mobile (écran < 768px). -->
        <div class="hero__thumbs">

          <!-- Grande vignette → prévisualise la slide 2 (tablette de chocolat) -->
          <button type="button" class="hero__thumb hero__thumb--lg" data-slide="2" aria-label="Aller à la slide 3">
            <img src="./assets/images/tablettes-de-chocolat-artisanal.webp" alt="Miniature tablette de chocolat" />
          </button>

          <!-- Petite vignette → prévisualise la slide 1 (chocolatier en atelier)
           Dépasse du bord droit grâce à margin-right négatif dans le CSS -->
          <button type="button" class="hero__thumb hero__thumb--sm" data-slide="1" aria-label="Aller à la slide 2">
            <img
              src="./assets/images/artisan-chocolatier-au-travail.webp"
              alt="Miniature chocolatier" />
          </button>

        </div>

      </section>

    </div><!-- /#app-wrapper -->