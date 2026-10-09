<?php



# Router

// Vérifie le rôle même si ce contrôleur est appelé depuis un autre point d'entrée.
if (!isset($_SESSION['user_role_id']) || (int) $_SESSION['user_role_id'] !== 1) {
    http_response_code(403);
    require_once "../view/publicView/404View.php";
    return;
}

// Charge la navigation privée et la page réservée aux administrateurs.
require_once "../view/privateView/inc/menuPrivateView.php";
require_once "../view/privateView/admin.php";
?>