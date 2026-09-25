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

<?php require_once __DIR__ . '/../view/publicView/inc/header.php' ?>

<?php require_once __DIR__ . '/../view/publicView/inc/footer.php' ?>
<script src="./js/scripts.js"></script>
</body>
</html>
