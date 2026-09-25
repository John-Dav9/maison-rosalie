/* JAVASCRIPT DU CAROUSEL — Maison Rosalie
   Script en JS natif (vanilla), aucune dépendance (pas de jQuery, etc.).

   Principe :
     - On récupère tous les slides, dots et vignettes.
     - La fonction goTo(index) active la slide n° index et désactive les autres,
       en ajoutant/retirant la classe .is-active sur les 3 groupes d'éléments.
     - Les dots, vignettes et flèches appellent goTo() au clic.
     - Un autoplay fait défiler les slides toutes les 6 secondes.
       Il se relance après chaque interaction (clic) pour éviter un changement
       de slide juste après que l'utilisateur ait cliqué. */

document.addEventListener('DOMContentLoaded', function () {

  // On récupère la section héro par son ID (si elle n'existe pas sur cette page, on arrête tout)
  let hero = document.getElementById('hero');
  if (!hero) return;

  // Récupération de tous les éléments du carousel
  let slides  = hero.querySelectorAll('.hero__slide');     // les 3 images
  let dots    = hero.querySelectorAll('.hero__dot');        // les 3 dots à gauche
  let thumbs  = hero.querySelectorAll('.hero__thumb');      // les 2 vignettes en bas
  let prevBtn = hero.querySelector('.hero__arrow--prev');   // flèche <
  let nextBtn = hero.querySelector('.hero__arrow--next');   // flèche >

  let current = 0;             // index de la slide actuellement affichée (0, 1 ou 2)
  let total   = slides.length; // nombre total de slides (3)
  let timer   = null;          // référence du setInterval pour l'autoplay

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
      let thumbTarget = parseInt(thumb.getAttribute('data-slide'), 10);
      thumb.classList.toggle('is-active', thumbTarget === current);
    });
  }

  // Passe à la slide suivante
  function next() { goTo(current + 1); }

  // Passe à la slide précédente
  function prev() { goTo(current - 1); }

  // Lance l'autoplay : change de slide toutes les 6 secondes
  function startAutoplay() {
    stopAutoplay();                  // on arrête l'ancien timer s'il existe
    timer = setInterval(next, 6000); // toutes les 6000ms = 6s
  }

  // Arrête l'autoplay
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

  // Au chargement : on affiche la slide 0 et on démarre l'autoplay
  goTo(0);
  startAutoplay();
});