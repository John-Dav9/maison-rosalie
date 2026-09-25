/* JAVASCRIPT DU CAROUSEL — Maison Rosalie
   Script en JS natif (vanilla), aucune dépendance (pas de jQuery, etc.).

   Principe :
     - On récupère tous les slides, dots et vignettes.
     - La fonction goTo(index) active la slide n° index et désactive les autres,
       en ajoutant/retirant la classe .is-active sur les 3 groupes d'éléments.
     - Les vignettes sont DYNAMIQUES : elles affichent toujours les 2 slides
       suivantes dans l'ordre (pas la slide active). Quand la slide change,
       les images et les data-slide des vignettes sont mis à jour.
     - Les dots, vignettes et flèches appellent goTo() au clic.
     - Un autoplay fait défiler les slides toutes les 6 secondes.
       Il se relance après chaque interaction (clic) pour éviter un changement
       de slide juste après que l'utilisateur ait cliqué.

   Relation entre les éléments :
     - Slides et dots → liés par index (dot 0 = slide 0, dot 1 = slide 1, etc.)
     - Vignettes → liées dynamiquement via data-slide (mis à jour à chaque goTo)
       car elles montrent les slides SUIVANTES, pas la slide active.

   Exemple avec 3 slides (couple=0, chocolatier=1, tablette=2) :
     - Slide 0 active → vignette grande = slide 1, vignette petite = slide 2
     - Slide 1 active → vignette grande = slide 2, vignette petite = slide 0
     - Slide 2 active → vignette grande = slide 0, vignette petite = slide 1 */

// On attend que tout le DOM soit chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function () {

  // 1. RÉCUPÉRATION DES ÉLÉMENTS

  // La section héro, identifiée par son ID
  // Si elle n'existe pas sur cette page (ex: page recette), on arrête tout
  let hero = document.getElementById('hero');
  if (!hero) return;

  // Les images du carousel (empilées, une seule visible à la fois)
  let slides = hero.querySelectorAll('.hero__slide');

  // Les dots de navigation à gauche (un par slide)
  let dots = hero.querySelectorAll('.hero__dot');

  // Les 2 vignettes en bas à droite (aperçu des slides suivantes)
  let thumbs = hero.querySelectorAll('.hero__thumb');

  // Les flèches < et > en bas au centre
  let prevBtn = hero.querySelector('.hero__arrow--prev');
  let nextBtn = hero.querySelector('.hero__arrow--next');

  // Le bloc texte (titre + accroche + bouton) et le voile sombre :
  // ils ne s'affichent QUE sur la slide 0 (couple en terrasse).
  // Sur les autres slides, l'image occupe tout l'espace sans texte par-dessus.
  let content = hero.querySelector('.hero__content');
  let overlay = hero.querySelector('.hero__overlay');

  // 2. VARIABLES D'ÉTAT

  // Index de la slide actuellement affichée (0, 1 ou 2)
  let current = 0;

  // Nombre total de slides (3 dans notre cas)
  let total = slides.length;

  // Référence du setInterval pour l'autoplay (null = pas d'autoplay en cours)
  let timer = null;

  // 3. COLLECTE DES SOURCES D'IMAGES
  // On récupère le chemin (src) de l'image de chaque slide et on le stocke
  // dans un tableau. Ce tableau sera utilisé dans goTo() pour mettre à jour
  // dynamiquement les images des vignettes quand on change de slide.
  // Exemple : slideSources[0] = "/public/assets/images/couple.png"
  //           slideSources[1] = "/public/assets/images/chocolatier.png"
  //           slideSources[2] = "/public/assets/images/tablette.jpg"
  let slideSources = [];
  slides.forEach(function (slide) {
    slideSources.push(slide.querySelector('img').getAttribute('src'));
  });

  // 4. FONCTION PRINCIPALE : goTo(index)
  // Affiche la slide n° index et met à jour tous les éléments liés.
  // Le modulo (%) permet de boucler : après la dernière slide on revient
  // à la 1re, et avant la 1re on va à la dernière.
  function goTo(index) {

    // Calcul de l'index avec boucle circulaire
    // Ex : goTo(3) avec 3 slides → (3 + 3) % 3 = 0 → retour à la 1re
    // Ex : goTo(-1) avec 3 slides → (-1 + 3) % 3 = 2 → dernière slide
    current = (index + total) % total;

    // 4a. SLIDES : ajouter .is-active sur la slide courante, retirer des autres
    // C'est le CSS qui gère le fondu (opacity 0→1 avec transition)
    slides.forEach(function (slide, i) {
      slide.classList.toggle('is-active', i === current);
    });

    // 4b. DOTS : même logique, le dot actif devient doré via le CSS
    dots.forEach(function (dot, i) {
      dot.classList.toggle('is-active', i === current);
    });

    // 4c. CONTENU + VOILE : visibles uniquement sur la slide 0 (couple).
    // Sur les autres slides, on les masque pour laisser l'image en plein écran.
    // On utilise style.display plutôt qu'une classe pour que ce soit immédiat
    // (pas de transition de fondu sur le texte, juste apparition/disparition).
    if (current === 0) {
      content.style.display = 'flex';  // flex car le conteneur est en flexbox
      overlay.style.display = 'block';
    } else {
      content.style.display = 'none';
      overlay.style.display = 'none';
    }

    // 4c. VIGNETTES : mise à jour dynamique des images et des data-slide
    // Chaque vignette montre la (i+1)ème slide APRÈS la slide courante,
    // en boucle circulaire. La première vignette (i=0) montre la slide
    // juste après, la deuxième (i=1) montre celle d'encore après.
    thumbs.forEach(function (thumb, i) {

      // Calcul de l'index de la slide que cette vignette doit montrer
      // Ex : current=0, i=0 → (0+0+1) % 3 = 1 → montre slide 1
      // Ex : current=0, i=1 → (0+1+1) % 3 = 2 → montre slide 2
      // Ex : current=2, i=0 → (2+0+1) % 3 = 0 → montre slide 0
      let targetIndex = (current + i + 1) % total;

      // On remplace le src de l'image de la vignette par celui de la slide cible
      thumb.querySelector('img').setAttribute('src', slideSources[targetIndex]);

      // On met à jour le data-slide pour que le clic mène à la bonne slide
      thumb.setAttribute('data-slide', targetIndex);

      // La première vignette (i=0) a toujours la bordure dorée (.is-active)
      // car c'est la "prochaine" slide dans l'ordre
      thumb.classList.toggle('is-active', i === 0);
    });
  }

  // 5. FONCTIONS DE NAVIGATION

  // Passe à la slide suivante (current + 1, boucle gérée par goTo)
  function next() { goTo(current + 1); }

  // Passe à la slide précédente (current - 1, boucle gérée par goTo)
  function prev() { goTo(current - 1); }

  // 6. AUTOPLAY

  // Lance le défilement automatique : change de slide toutes les 6 secondes
  function startAutoplay() {
    stopAutoplay();                  // on arrête l'ancien timer s'il existe
    timer = setInterval(next, 6000); // toutes les 6000ms = 6 secondes
  }

  // Arrête le défilement automatique
  function stopAutoplay() {
    if (timer) clearInterval(timer);
  }

  // 7. ÉVÉNEMENTS CLIC

  // Clic sur un dot → aller à la slide correspondante (même index)
  // + relancer l'autoplay pour éviter un changement immédiat après le clic
  dots.forEach(function (dot, i) {
    dot.addEventListener('click', function () {
      goTo(i);
      startAutoplay();
    });
  });

  // Clic sur une vignette → lire son data-slide (mis à jour dynamiquement
  // par goTo) pour savoir vers quelle slide naviguer
  thumbs.forEach(function (thumb) {
    thumb.addEventListener('click', function () {
      let target = parseInt(thumb.getAttribute('data-slide'), 10);
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

  // 8. INITIALISATION
  // Au chargement de la page : on affiche la slide 0 et on démarre l'autoplay
  goTo(0);
  startAutoplay();
});