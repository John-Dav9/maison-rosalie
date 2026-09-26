<?php
/*
 * Contrôleur frontal : toutes les pages du site passent par ce fichier.
 * La page demandée est lue dans l'URL : index.php?pg=about, index.php?pg=contact, ...
 */
session_start();

require_once __DIR__ . '/../config.init.php';

// page courante : 'home' par défaut ou si la page demandée n'existe pas
$page = $_GET['pg'] ?? 'home';
if (!in_array($page, VALID_PAGES, true)) {
    $page = 'home';
}

require_once __DIR__ . '/../controller/navbarController.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Playfair Display : police des titres et de la page À propos -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Font Awesome : nous donne les icônes (YouTube, Facebook, Instagram...) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- couleurs de la marque, utilisables comme classes Tailwind : text-gold, bg-espresso, ... -->
    <style type="text/tailwindcss">
        @theme {
            --color-espresso: #1e100c;
            --color-cream: #f5ecd7;
            --color-gold: #c8b273;
        }
    </style>
    <title>Document</title>
</head>
<body>

<?php require_once __DIR__ . '/../view/publicView/inc/navbar.php' ?>

<?php
// le héro n'apparaît que sur la page d'accueil
if ($page === 'home') {
    require_once __DIR__ . '/../view/publicView/inc/header.php';
}

// contenu de la page demandée ($page est déjà validé avec VALID_PAGES)
require_once __DIR__ . '/../view/publicView/' . $page . '.php';
?>

<?php require_once __DIR__ . '/../view/publicView/inc/footer.php' ?>
<script src="./js/scripts.js"></script>
<?php if ($page === 'about'): ?>
    <!-- compteurs animés des chiffres clés -->
    <script src="./js/about.js"></script>
<?php endif; ?>
</body>
</html>
