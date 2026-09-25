<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maison Rosalie</title>

  <!-- Chargement de la police "Playfair Display" depuis Google Fonts, utilisée pour le titre -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">


  <!-- STYLES DE LA SECTION HÉRO

    Tout le CSS est ici dans le fichier, aucune dépendance externe
    (pas de Tailwind, pas de FlyonUI, pas de fichier .css séparé).

    Convention de nommage : BEM (Block Element Modifier)
      - .hero            = le bloc principal (la section entière)
      - .hero__slide      = un élément du bloc (une image du carousel)
      - .hero__thumb--lg  = un modifier (variante grande de la vignette) -->
  <style>
    /* RESET MINIMAL
       Supprime les marges/paddings par défaut du navigateur pour que
       le rendu soit identique sur Chrome, Firefox, Safari, Edge, etc. */

    *,
    *::before,
    *::after {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      /* les paddings/bordures sont inclus dans la largeur */
    }

    /* Variable CSS globale : la couleur dorée utilisée partout dans le héro */
    :root {
      --hero-gold: #cda45e;
    }

    /* Police par défaut pour tout le body */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* CONTENEUR PRINCIPAL (.hero)
       La section prend toute la largeur et toute la hauteur de l'écran.
       C'est le "cadre" dans lequel tout le reste est positionné en absolute. */
    .hero {
      position: relative;
      /* référence pour tous les éléments en position: absolute à l'intérieur */
      width: 100%;
      /* toute la largeur */
      height: 100vh;
      /* toute la hauteur de la fenêtre */
      min-height: 600px;
      /* hauteur minimum si l'écran est très petit */
      overflow: hidden;
      /* cache ce qui dépasse (ex: la 2e vignette coupée à droite) */
      background: #000;
      /* fond noir visible pendant le chargement des images */
    }

    /* SLIDES DU CAROUSEL
       Les 3 images sont empilées les unes sur les autres (position absolute).
       Elles sont toutes invisibles (opacity: 0) sauf celle qui a la
       classe .is-active (opacity: 1). Le changement se fait en fondu
       grâce à transition: opacity. */
    .hero__slides {
      position: relative;
      width: 100%;
      height: 100%;
    }

    /* Chaque slide occupe tout l'espace et est invisible par défaut */
    .hero__slide {
      position: absolute;
      inset: 0;
      /* raccourci pour top:0 right:0 bottom:0 left:0 */
      opacity: 0;
      /* invisible par défaut */
      transition: opacity .8s ease;
      /* fondu de 0.8 secondes */
    }

    /* La slide active devient visible et passe devant les autres */
    .hero__slide.is-active {
      opacity: 1;
      z-index: 1;
    }

    /* L'image à l'intérieur de chaque slide remplit tout l'espace
       sans déformation grâce à object-fit: cover (elle est recadrée si besoin) */
    .hero__slide img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      /* supprime l'espace fantôme sous les images inline */
    }

    /* VOILE SOMBRE (OVERLAY)
       Un dégradé semi-transparent par-dessus l'image, plus opaque à gauche
       et plus transparent à droite. Ça permet au texte blanc d'être lisible
       même sur des images claires.
       pointer-events: none = les clics passent à travers le voile. */
    .hero__overlay {
      position: absolute;
      inset: 0;
      /* couvre toute la section */
      z-index: 2;
      /* au-dessus des slides (z-index: 1) */
      pointer-events: none;
      /* ne bloque pas les clics sur les éléments en-dessous */
      background: linear-gradient(to right,
          rgba(0, 0, 0, .65) 0%,
          /* gauche : assez sombre */
          rgba(0, 0, 0, .45) 40%,
          rgba(0, 0, 0, .25) 65%,
          rgba(0, 0, 0, .15) 100%
          /* droite : presque transparent */
        );
    }

    /* BLOC TEXTE (titre + accroche + bouton)
       Positionné en absolute sur la partie droite de l'image,
       centré verticalement avec top + translateY(-50%).
       Flexbox en colonne pour empiler titre, texte et bouton. */
    .hero__content {
      position: absolute;
      top: 42%;
      /* un peu au-dessus du centre pour laisser de la place aux vignettes */
      right: 2rem;
      /* marge à droite (mobile) */
      transform: translateY(-50%);
      /* astuce pour centrer verticalement */
      z-index: 3;
      /* au-dessus du voile */
      max-width: 28rem;
      /* largeur max du bloc texte */
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      /* tout aligné à gauche dans le bloc */
      gap: 1.5rem;
      /* espacement entre titre, texte et bouton */
    }

    /* Sur grand écran : plus de marge à droite et bloc légèrement plus large */
    @media (min-width: 1024px) {
      .hero__content {
        right: 3.5rem;
        max-width: 30rem;
      }
    }

    /* Titre "Maison Rosalie" */
    .hero__title {
      font-family: 'Playfair Display', Georgia, serif;
      /* police élégante, avec fallback */
      font-weight: 400;
      font-size: 3.5rem;
      /* taille mobile */
      color: #fff;
      line-height: 1.1;
      /* interligne serré pour un rendu "display" */
    }

    /* Taille du titre sur tablette */
    @media (min-width: 768px) {
      .hero__title {
        font-size: 4.5rem;
      }
    }

    /* Taille du titre sur desktop */
    @media (min-width: 1024px) {
      .hero__title {
        font-size: 5.5rem;
      }
    }

    /* Trait doré horizontal sous le titre */
    .hero__divider {
      display: block;
      width: 6rem;
      /* largeur du trait */
      height: 2px;
      /* épaisseur du trait */
      margin-top: 1rem;
      /* espace entre le titre et le trait */
      background: var(--hero-gold);
      /* couleur dorée définie dans :root */
    }

    /* Paragraphe d'accroche */
    .hero__text {
      color: rgba(255, 255, 255, .85);
      /* blanc légèrement transparent */
      font-size: .95rem;
      line-height: 1.75;
      /* interligne aéré pour la lisibilité */
      text-align: left;
    }

    /* Bouton "découvrir"
       Forme "pilule" (border-radius très grand), bordure blanche au repos,
       fond doré au survol. align-self: center le centre dans la colonne flex. */
    .hero__cta {
      align-self: center;
      /* centré horizontalement dans le bloc */
      display: inline-block;
      border: 1px solid rgba(255, 255, 255, .6);
      /* bordure blanche semi-transparente */
      color: #fff;
      text-decoration: none;
      /* pas de soulignement (c'est un <a>) */
      padding: 1rem 3.5rem;
      /* grand padding pour un bouton bien visible */
      border-radius: 999px;
      /* forme "pilule" */
      font-size: 1.1rem;
      letter-spacing: .04em;
      /* léger espacement entre les lettres */
      transition: background .25s ease, color .25s ease, border-color .25s ease;
    }

    /* Au survol : fond doré, texte foncé */
    .hero__cta:hover {
      background: var(--hero-gold);
      border-color: var(--hero-gold);
      color: #111;
    }

    /* DOTS DE NAVIGATION (colonne verticale à gauche)
       3 petits cercles indiquant la slide active.
       Celui avec .is-active est rempli en doré, les autres sont vides. */
    .hero__dots {
      position: absolute;
      top: 50%;
      /* centré verticalement */
      left: 2rem;
      /* à gauche de l'image */
      transform: translateY(-50%);
      z-index: 3;
      display: flex;
      flex-direction: column;
      /* empilés verticalement */
      gap: .85rem;
      /* espace entre chaque dot */
    }

    /* Chaque dot : petit cercle avec contour blanc, fond transparent */
    .hero__dot {
      width: .7rem;
      height: .7rem;
      border-radius: 50%;
      /* forme ronde */
      border: 1px solid rgba(255, 255, 255, .5);
      padding: 0;
      cursor: pointer;
      background: transparent;
      transition: background .2s ease, border-color .2s ease;
    }

    /* Dot actif : rempli et bordé en doré */
    .hero__dot.is-active {
      background: var(--hero-gold);
      border-color: var(--hero-gold);
    }

    /* VIGNETTES (en bas à droite)
       2 miniatures cliquables :
         - la 1re (--lg) est grande et bien visible
         - la 2e (--sm) est plus petite et dépasse du bord droit
       Masquées en mobile (display: none) car pas assez de place. */
    .hero__thumbs {
      position: absolute;
      bottom: 3rem;
      right: 0;
      /* collé au bord droit pour que la 2e vignette dépasse */
      z-index: 3;
      display: none;
      /* masqué en mobile */
      align-items: flex-end;
      /* les 2 vignettes sont alignées en bas */
      gap: .75rem;
    }

    /* Affichées à partir de 768px (tablette et desktop) */
    @media (min-width: 768px) {
      .hero__thumbs {
        display: flex;
      }
    }

    /* Style commun aux 2 vignettes */
    .hero__thumb {
      padding: 0;
      border: 3px solid transparent;
      /* bordure invisible par défaut */
      border-radius: .35rem;
      /* coins légèrement arrondis */
      overflow: hidden;
      /* l'image est recadrée dans le cadre */
      cursor: pointer;
      background: none;
      transition: border-color .2s ease;
    }

    /* La vignette active a une bordure dorée */
    .hero__thumb.is-active {
      border-color: var(--hero-gold);
    }

    /* Image à l'intérieur des vignettes */
    .hero__thumb img {
      display: block;
      object-fit: cover;
      /* recadrée sans déformation */
    }

    /* Grande vignette (1re) */
    .hero__thumb--lg {
      width: 12rem;
      height: 16rem;
    }

    .hero__thumb--lg img {
      width: 100%;
      height: 100%;
    }

    /* Plus grande sur desktop */
    @media (min-width: 1024px) {
      .hero__thumb--lg {
        width: 13rem;
        height: 17rem;
      }
    }

    /* Petite vignette (2e)
       margin-right négatif = elle dépasse du bord droit de la section,
       ce qui donne l'effet "coupée" comme sur la maquette */
    .hero__thumb--sm {
      width: 8rem;
      height: 12rem;
      margin-right: -2rem;
      /* dépasse du bord droit */
    }

    .hero__thumb--sm img {
      width: 100%;
      height: 100%;
    }

    /* Plus grande sur desktop */
    @media (min-width: 1024px) {
      .hero__thumb--sm {
        width: 9rem;
        height: 13rem;
      }
    }

    /* FLÈCHES < > (centrées en bas de la section)
       Deux petits boutons carrés avec une bordure fine, centrés
       horizontalement grâce à left: 50% + translateX(-50%). */
    .hero__arrows {
      position: absolute;
      bottom: .75rem;
      left: 50%;
      transform: translateX(-50%);
      /* centrage horizontal */
      z-index: 3;
      display: flex;
      gap: .15rem;
      /* petit espace entre les 2 flèches */
    }

    /* Chaque flèche : carré, bordure fine, fond transparent */
    .hero__arrow {
      width: 1.75rem;
      height: 1.75rem;
      border: 1px solid rgba(255, 255, 255, .3);
      border-radius: 0;
      /* coins carrés (pas arrondis) */
      background: transparent;
      color: rgba(255, 255, 255, .6);
      font-size: .85rem;
      line-height: 1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background .2s ease;
    }

    /* Au survol : léger fond blanc */
    .hero__arrow:hover {
      background: rgba(255, 255, 255, .1);
    }
  </style>
</head>

<body>


  <!-- SECTION HÉRO DE LA PAGE D'ACCUEIL

    Structure :
      1. .hero__slides   → les 3 images plein cadre (empilées, fondu enchaîné)
      2. .hero__overlay   → voile sombre pour la lisibilité du texte
      3. .hero__content   → titre + texte d'accroche + bouton "découvrir"
      4. .hero__dots      → 3 points de navigation verticaux (gauche)
      5. .hero__thumbs    → 2 vignettes cliquables (bas droite)
      6. .hero__arrows    → flèches < > (bas centre)

    Le script JS en bas de page synchronise slides, dots et vignettes
    sur le même index : quand on clique sur un dot ou une vignette,
    la slide correspondante s'affiche. -->
  <section class="hero">

    <!-- 1. IMAGES DU CAROUSEL -->
    <div class="hero__slides">

      <!-- Slide 1 : couple en terrasse (visible par défaut grâce à .is-active) -->
      <div class="hero__slide is-active">
        <img
          src="/public/assets/images/leto_black_couple_eating_chocolate_on_a_terrasse_in_front_of__258a1283-22c6-493-a05a-c4bebf0b6ebc_1.png"
          alt="Couple dégustant du chocolat en terrasse" />
      </div>

      <!-- Slide 2 : chocolatier en atelier -->
      <div class="hero__slide">
        <img
          src="/public/assets/images/leto_man_making_chocolate_in_a_rustic_store_with_a_window_beh_ddff26c9-7594-4222-90d8-c5de9d991462_2.png"
          alt="Chocolatier au travail dans son atelier" />
      </div>

      <!-- Slide 3 : tablette de chocolat artisanal -->
      <div class="hero__slide">
        <img src="/public/assets/images/d43577a012cd4c1e75acb3d4408c1fa3.jpg" alt="Tablette de chocolat artisanal" />
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
        Hipster ipsum tattooed brunch I'm baby. Yolo taiyaki
        paleo bicycle waistcoat hoodie hipster hot v plz.
        Ascot lo-fi cray mumblecore prism brunch yuccie
        kitsch. Cronut tumeric book knausgaard blog santo
        kogi plz.
      </p>

      <!-- Bouton d'appel à l'action -->
      <a href="#decouvrir" class="hero__cta">découvrir</a>

    </div>

    <!-- 4. DOTS VERTICAUX (gauche) -->
    <!-- 3 petits cercles, un par slide. Celui avec .is-active est doré. -->
    <div class="hero__dots">
      <button type="button" class="hero__dot is-active" aria-label="Slide 1"></button>
      <button type="button" class="hero__dot" aria-label="Slide 2"></button>
      <button type="button" class="hero__dot" aria-label="Slide 3"></button>
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
        <img src="/public/assets/images/d43577a012cd4c1e75acb3d4408c1fa3.jpg" alt="Miniature tablette de chocolat" />
      </button>

      <!-- Petite vignette → prévisualise la slide 1 (chocolatier en atelier)
           Dépasse du bord droit grâce à margin-right négatif dans le CSS -->
      <button type="button" class="hero__thumb hero__thumb--sm" data-slide="1" aria-label="Aller à la slide 2">
        <img
          src="/public/assets/images/leto_man_making_chocolate_in_a_rustic_store_with_a_window_beh_ddff26c9-7594-4222-90d8-c5de9d991462_2.png"
          alt="Miniature chocolatier" />
      </button>

    </div>

    <!-- 6. FLÈCHES < > (bas centre) -->
    <!-- 2 boutons carrés pour passer à la slide précédente/suivante -->
    <div class="hero__arrows">
      <button type="button" class="hero__arrow hero__arrow--prev" aria-label="Slide précédente">&#8249;</button>
      <button type="button" class="hero__arrow hero__arrow--next" aria-label="Slide suivante">&#8250;</button>
    </div>

  </section>

  <!-- JAVASCRIPT DU CAROUSEL

    Script en JS natif (vanilla), aucune dépendance (pas de jQuery, etc.).

    Principe :
      - On récupère tous les slides, dots et vignettes.
      - La fonction goTo(index) active la slide n° index et désactive les autres,
        en ajoutant/retirant la classe .is-active sur les 3 groupes d'éléments.
      - Les dots, vignettes et flèches appellent goTo() au clic.
      - Un autoplay fait défiler les slides toutes les 6 secondes.
        Il se relance après chaque interaction (clic) pour éviter un changement
        de slide juste après que l'utilisateur ait cliqué. -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {

      // On récupère la section héro (si elle n'existe pas, on arrête tout)
      var hero = document.querySelector('.hero');
      if (!hero) return;

      // Récupération de tous les éléments du carousel
      var slides = hero.querySelectorAll('.hero__slide');   // les 3 images
      var dots = hero.querySelectorAll('.hero__dot');      // les 3 dots à gauche
      var thumbs = hero.querySelectorAll('.hero__thumb');    // les 2 vignettes en bas
      var prevBtn = hero.querySelector('.hero__arrow--prev'); // flèche <
      var nextBtn = hero.querySelector('.hero__arrow--next'); // flèche >

      var current = 0;             // index de la slide actuellement affichée (0, 1 ou 2)
      var total = slides.length; // nombre total de slides (3)
      var timer = null;          // référence du setInterval pour l'autoplay

      /* goTo(index) — Affiche la slide n° index.
        Le modulo (%) permet de boucler : après la dernière slide on revient à la 1re,
        et avant la 1re on va à la dernière.
        On ajoute .is-active sur l'élément correspondant dans chaque groupe
        (slides, dots, vignettes) et on le retire de tous les autres. */
      function goTo(index) {
        current = (index + total) % total;

        // Pour chaque slide : ajouter .is-active si c'est la bonne, sinon retirer
        slides.forEach(function (slide, i) {
          slide.classList.toggle('is-active', i === current);
        });

        // Même chose pour les dots
        dots.forEach(function (dot, i) {
          dot.classList.toggle('is-active', i === current);
        });

        // Même chose pour les vignettes :
        // Ici on ne se base PAS sur l'index de la vignette dans le DOM,
        // mais sur son attribut data-slide (qui dit à quelle slide elle correspond).
        // Ex: data-slide="2" → la vignette est active quand la slide 2 est affichée.
        thumbs.forEach(function (thumb) {
          var thumbTarget = parseInt(thumb.getAttribute('data-slide'), 10);
          thumb.classList.toggle('is-active', thumbTarget === current);
        });
      }

      /** Passe à la slide suivante */
      function next() {goTo(current + 1);}

      /** Passe à la slide précédente */
      function prev() {goTo(current - 1);}

      /** Lance l'autoplay : change de slide toutes les 6 secondes */
      function startAutoplay() {
        stopAutoplay();                    // on arrête l'ancien timer s'il existe
        timer = setInterval(next, 6000);   // toutes les 6000ms = 6s
      }

      /* Arrête l'autoplay */
      function stopAutoplay() {
        if (timer) clearInterval(timer);
      }

      // Clic sur un dot → aller à la slide correspondante + relancer l'autoplay
      dots.forEach(function (dot, i) {
        dot.addEventListener('click', function () {
          goTo(i);
          startAutoplay();
        });
      });

      // Clic sur une vignette → lire son data-slide pour savoir vers quelle slide aller
      thumbs.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
          var target = parseInt(thumb.getAttribute('data-slide'), 10);
          goTo(target);
          startAutoplay();
        });
      });

      // Clic sur la flèche "précédent" → slide précédente + relancer l'autoplay
      if (prevBtn) {
        prevBtn.addEventListener('click', function () {
          prev();
          startAutoplay();
        });
      }

      // Clic sur la flèche "suivant" → slide suivante + relancer l'autoplay
      if (nextBtn) {
        nextBtn.addEventListener('click', function () {
          next();
          startAutoplay();
        });
      }

      // Au chargement : on affiche la slide 0 et on démarre l'autoplay
      goTo(0);
      startAutoplay();
    });
  </script>

</body>

</html>