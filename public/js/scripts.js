/* JAVASCRIPT DU CAROUSEL — Maison Rosalie
   Script en JS natif (vanilla), aucune dépendance (pas de jQuery, etc.).

   Principe :
     - On récupère tous les slides, dots et vignettes.
     - La fonction goTo(index) active la slide n° index et désactive les autres,
       en ajoutant/retirant la classe .is-active sur les 3 groupes d'éléments.
     - Les vignettes sont DYNAMIQUES : elles affichent toujours les 2 slides
       suivantes dans l'ordre (pas la slide active). Quand la slide change,
       les images des vignettes sont mises à jour avec un effet de fondu.
     - Les dots, vignettes et flèches appellent goTo() au clic.
     - Un autoplay fait défiler les slides toutes les 8 secondes.
       Il se relance après chaque interaction (clic) pour éviter un changement
       de slide juste après que l'utilisateur ait cliqué.

   Relation entre les éléments :
     - Slides et dots → liés par index (dot 0 = slide 0, dot 1 = slide 1, etc.)
     - Vignettes → la cible est CALCULÉE au moment du clic avec la formule
       (current + i + 1) % total, ce qui garantit que le clic mène toujours
       à l'image affichée, sans décalage possible.

   Exemple avec 4 slides (couple=0, chocolatier=1, tablette=2, fèves=3) :
     - Slide 0 active → vignette grande = slide 1, vignette petite = slide 2
     - Slide 1 active → vignette grande = slide 2, vignette petite = slide 3
     - Slide 2 active → vignette grande = slide 3, vignette petite = slide 0
     - Slide 3 active → vignette grande = slide 0, vignette petite = slide 1 */

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

  // Le bloc texte (titre + accroche + bouton) et le voile sombre :
  // ils ne s'affichent QUE sur la slide 0 (couple en terrasse).
  // Sur les autres slides, l'image occupe tout l'espace sans texte par-dessus.
  let content = hero.querySelector('.hero__content');
  let overlay = hero.querySelector('.hero__overlay');

  // 2. VARIABLES D'ÉTAT

  // Index de la slide actuellement affichée (0, 1, 2 ou 3)
  let current = 0;

  // Nombre total de slides (4 dans notre cas)
  let total = slides.length;

  // Référence du setInterval pour l'autoplay (null = pas d'autoplay en cours)
  let timer = null;

  // 3. COLLECTE ET PRÉCHARGEMENT DES IMAGES

  // On récupère le chemin (src) de l'image de chaque slide et on le stocke
  // dans un tableau. Ce tableau sera utilisé dans goTo() pour mettre à jour
  // dynamiquement les images des vignettes quand on change de slide.
  let slideSources = [];
  slides.forEach(function (slide) {
    slideSources.push(slide.querySelector('img').getAttribute('src'));
  });

  // On précharge toutes les images dans le cache du navigateur.
  // Ça permet que le changement d'image dans les vignettes soit instantané
  // (pas d'attente de chargement réseau).
  slideSources.forEach(function (src) {
    let img = new Image();
    img.src = src;
  });

  // 4. FONCTION PRINCIPALE : goTo(index)
  // Affiche la slide n° index et met à jour tous les éléments liés.
  // Le modulo (%) permet de boucler : après la dernière slide on revient
  // à la 1re, et avant la 1re on va à la dernière.
  function goTo(index) {

    // Calcul de l'index avec boucle circulaire
    // Ex : goTo(4) avec 4 slides → (4 + 4) % 4 = 0 → retour à la 1re
    // Ex : goTo(-1) avec 4 slides → (-1 + 4) % 4 = 3 → dernière slide
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
    if (current === 0) {
      content.style.display = 'flex';
      overlay.style.display = 'block';
    } else {
      content.style.display = 'none';
      overlay.style.display = 'none';
    }

    // 4d. VIGNETTES : mise à jour avec effet de fondu.
    // Étape 1 : on passe l'opacité de l'image à 0 (fondu sortant, 0.3s via CSS)
    // Étape 2 : après 300ms, on change le src et on repasse l'opacité à 1 (fondu entrant)
    // Comme ça l'ancienne image disparaît avant que la nouvelle apparaisse,
    // et il n'y a jamais de décalage entre l'image visible et la slide cible.
    thumbs.forEach(function (thumb, i) {

      // L'index de la slide que cette vignette doit montrer :
      // la (i+1)ème slide après la slide courante, en boucle
      let targetIndex = (current + i + 1) % total;

      // Récupérer l'élément <img> de la vignette
      let thumbImg = thumb.querySelector('img');

      // Fondu sortant : rendre l'image transparente
      thumbImg.style.opacity = '0';

      // Après la durée du fondu (300ms), changer l'image et la faire réapparaître
      setTimeout(function () {
        thumbImg.setAttribute('src', slideSources[targetIndex]);
        thumbImg.style.opacity = '1';
      }, 300);

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

  // Lance le défilement automatique : change de slide toutes les 8 secondes
  function startAutoplay() {
    stopAutoplay();                  // on arrête l'ancien timer s'il existe
    timer = setInterval(next, 8000); // toutes les 8000ms = 8 secondes
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

  // Clic sur une vignette → on CALCULE la cible directement à partir de
  // l'index de la vignette (i) et de la slide courante (current).
  // Formule : (current + i + 1) % total
  // C'est la MÊME formule que dans goTo() pour mettre à jour les vignettes,
  // donc l'image affichée et la slide cible sont TOUJOURS synchronisées.
  // On n'utilise PAS data-slide car il peut être désynchronisé de l'image
  // visible (si l'image n'a pas fini de charger quand data-slide a déjà changé).
  thumbs.forEach(function (thumb, i) {
    thumb.addEventListener('click', function () {
      let target = (current + i + 1) % total;
      goTo(target);
      startAutoplay();
    });
  });

  // 8. INITIALISATION
  // Au chargement de la page : on affiche la slide 0 et on démarre l'autoplay
  goTo(0);
  startAutoplay();
});