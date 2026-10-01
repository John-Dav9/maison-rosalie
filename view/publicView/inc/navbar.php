<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maison Rosalie</title>

  <!-- Police serif pour le titre "Maison Rosalie" -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Styles da la navbar -->
  <link rel="stylesheet" href="../../../public/css/style.css">
</head>

<?php
/* Vue : barre de navigation commune à toutes les pages (FE-04, FE-05, FE-06).
Cette vue ne fait qu'afficher (QC-02) : aucune requête SQL ici.
Le contrôleur doit préparer les variables suivantes avant de l'inclure :
string $page page courante, validée avec VALID_PAGES ('home', 'about', ...)
array $navRecipes liste légère des recettes (BE-10) : [['slug' => ..., 'title' => ...], ...]
array|null $currentUser  utilisateur connecté (['username' => ..., 'role' => 'user' | 'admin'])
ou null si le visiteur n'est pas connecté
string $csrfToken jeton CSRF de la session, utilisé pour la déconnexion (SEC-03)
Style : Tailwind. Les couleurs de la marque (espresso, cream, gold) sont définies dans public/index.php. */

// valeurs par défaut au cas où le contrôleur n'aurait pas défini ces variables :
// évite les avertissements PHP "Variable non définie" si la vue est incluse sans contexte complet
$page        = $page        ?? '';
$navRecipes  = $navRecipes  ?? [];
$currentUser = $currentUser ?? null;
$csrfToken   = $csrfToken   ?? '';

// liens du menu, dans l'ordre de la maquette : nom de la page => libellé
$menuLinks = [
    'home'    => 'Accueil',
    'about'   => 'À propos',
    'recipe'  => 'Recettes',
    'contact' => 'Contact',
];

// seul un administrateur voit le lien vers son espace
if ($currentUser !== null && $currentUser['role'] === 'admin') {
    $menuLinks['admin'] = 'Admin';
}
?>
<!-- Accueil : la navbar est transparente et posée par-dessus le haut de la page (maquette Figma).
    Le léger dégradé sombre garde le texte lisible quelle que soit l'image du hero (FE-01).
    Pour la page d'accueil : le hero doit commencer tout en haut de la page, il passe sous la navbar.
    Autres pages : fond espresso classique, la navbar prend sa place normale. -->
<header class="site-nav <?= $page === 'home' ? 'absolute inset-x-0 top-0 z-50 bg-linear-to-b from-black/60 to-transparent' : 'bg-espresso' ?> text-cream">
    <nav class="site-nav__bar mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-4" aria-label="Navigation principale">

        <!-- logo : retour à l'accueil (FE-02) -->
        <a href="./?pg=home" class="site-nav__logo">
            <img src="../../../public/assets/logo/logo-text-only.svg" alt="Maison Rosalie, retour à l'accueil" class="h-8">
        </a>

        <!-- burger (mobile) : la case à cocher affiche / masque le menu, sans JavaScript (FE-06) -->
        <input type="checkbox" id="menuToggle" class="site-nav__toggle peer sr-only" aria-label="Afficher le menu">
        <label for="menuToggle" class="site-nav__burger cursor-pointer text-3xl md:hidden" aria-hidden="true">&#9776;</label>

        <!-- sur mobile, le menu déplié a un fond plein pour rester lisible par-dessus le hero -->
        <div class="site-nav__collapse hidden w-full flex-col gap-6 rounded-md bg-espresso p-4 peer-checked:flex md:flex md:bg-transparent md:p-0 md:w-auto md:flex-1 md:flex-row md:items-center md:justify-between">
            <ul class="site-nav__menu flex flex-col gap-4 text-lg md:ml-10 md:flex-row md:gap-8">
                <?php foreach ($menuLinks as $link => $label): ?>
                    <?php
                    // la page courante est mise en évidence en doré (FE-06)
                    $isActive = $link === $page;
                    $linkClass = 'site-nav__link' . ($isActive ? ' is-active' : '');
                    ?>

                    <?php if ($link === 'recipe'): ?>
                        <!-- Recettes : menu déroulant natif (<details>), alimenté par la base de données (FE-05) -->
                        <li class="relative">
                            <details>
                                <summary class="cursor-pointer <?= $linkClass ?>"><?= $label ?></summary>
                                <ul class="mt-2 flex min-w-56 flex-col gap-2 rounded-md bg-espresso p-4 md:absolute md:left-0 md:shadow-lg">
                                    <li><a href="./?pg=recipe" class="hover:text-gold">Toutes les recettes</a></li>
                                    <?php foreach ($navRecipes as $recipe): ?>
                                        <li>
                                            <!-- données venant de la base : toujours échappées (SEC-02) -->
                                            <a href="./?pg=recipe&amp;slug=<?= urlencode($recipe['slug']) ?>" class="hover:text-gold">
                                                <?= htmlspecialchars($recipe['title']) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </details>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="./?pg=<?= $link ?>" class="<?= $linkClass ?>"
                               <?= $isActive ? 'aria-current="page"' : '' ?>><?= $label ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>

            <!-- espace de droite : icônes toujours visibles + bouton connexion/déconnexion (FE-04) -->
            <div class="site-nav__actions flex items-center gap-4">

                <!-- icônes SVG écrites dans le HTML : aucune bibliothèque d'icônes à charger -->
                <!-- profil (bonhomme) : vers la page profil, + nom de l'utilisateur si connecté -->
                <a href="./?pg=profile" class="site-nav__icon flex items-center gap-2 hover:text-gold" aria-label="Mon profil">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    <?php if ($currentUser !== null): ?>
                        <?= htmlspecialchars($currentUser['username']) ?>
                    <?php endif; ?>
                </a>

                <!-- recettes favorites (cœur) -->
                <a href="./?pg=favorites" class="site-nav__icon hover:text-gold" aria-label="Mes recettes favorites">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                </a>

                <!-- bouton : Connexion pour un visiteur, Déconnexion pour un membre (FE-04) -->
                <?php if ($currentUser === null): ?>
                    <!-- ouvre la fenêtre modale <dialog id="loginModal"> quand elle sera codée (FE-50) -->
                    <button type="button" command="show-modal" commandfor="loginModal"
                            class="site-nav__login w-fit cursor-pointer rounded-md bg-cream px-4 py-1.5 text-espresso hover:bg-gold">
                        Connexion
                    </button>
                <?php else: ?>
                    <!-- la déconnexion modifie la session : POST + jeton CSRF (SEC-03) -->
                    <form action="./?pg=logout" method="post">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                        <button type="submit" class="cursor-pointer rounded-md border border-cream px-3 py-1 text-sm hover:bg-cream hover:text-espresso">
                            Déconnexion
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

    </nav>
</header>