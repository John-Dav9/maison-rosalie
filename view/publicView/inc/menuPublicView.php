<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome : nous donne les icônes (YouTube, Facebook, Instagram...) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Police manuscrite pour le logo / Footer /  Maison Rosalie -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Allura&display=swap">
    <title>Maison Rosalie</title>
</head>

<body>

    <nav class="mx-auto flex flex-wrap items-center justify-between gap-4 px-6 py-4 fixed z-20 w-full texte-white menu" aria-label="Navigation principale">

        <a href="./?pages=home">
            <img src="./assets/logo/logo-text-only.svg" alt="Maison Rosalie, retour à l'accueil" class="h-8">
        </a>

        <input type="checkbox" id="menuToggle" class="peer sr-only" aria-label="Afficher le menu">
        <label for="menuToggle" class="cursor-pointer text-3xl md:hidden" aria-hidden="true">&#9776;</label>

        <div class="hidden w-full flex-col gap-6 rounded-md bg-espresso p-4 peer-checked:flex md:flex md:bg-transparent md:p-0 md:w-auto md:flex-1 md:flex-row md:items-center md:justify-between">
            <ul class="flex flex-col gap-4 text-lg md:ml-10 md:flex-row md:gap-8">
                <li><a href="./?pages=home" class="hover:text-gold">Accueil</a></li>
                <li><a href="./?pages=about" class="hover:text-gold">À propos</a></li>
                <li><a href="./?pages=recipe" class="hover:text-gold">Recettes</a></li>
                <li><a href="./?pages=contact" class="hover:text-gold">Contact</a></li>
            </ul>

            <button id="connexion" type="button" command="show-modal" commandfor="loginModal"
                class="text-white box-border border border-transparent focus:ring-4 focus:ring-warning-medium shadow-xs font-medium leading-5 text-sm px-4 py-2.5 focus:outline-none">
                Connexion
            </button>
        </div>

    </nav>