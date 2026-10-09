<?php
# formateur/public/index.php

/**
 * Contrôleur frontal, seul fichier accessible du site
 */

/**
 * Appel des dépendances
 */

// fichier de configuration
require_once "../config.php";
# 1 ) Ce fichier est le point d'entrée de l'application, il est appelé par le serveur web (Apache, Nginx, IIS, etc.) et il va charger les fichiers nécessaires pour exécuter l'application. - Comment l'appelle-t-on ? 

# 2) que lance-t-on à cette ligne ?
session_start();

# 3) que charge-t-on à cette ligne ?
require_once "../config.php";
# 4) que charge-t-on à ces lignes ?
// require_once "../model/PostModel.php";# table post
// require_once "../model/CategoryModel.php";# table category
// require_once "../model/UserModel.php";# table user


# 5 ) Nous essayons delancer quelle type d'objet, et pourquoi?
try {
    $connectPDO = new PDO(
        DB_DSN,
        DB_LOGIN,
        DB_PWD
    );
    # 6 ) activation de quoi
    $connectPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    # 7 ) En quoi voulons-nous que les résultats soient retournés par défaut ? 
    $connectPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    # 8 ) le catch est appelé dans quel cas
} catch (Exception $e) {
    # 9 ) et que fait-il?
    die($e->getMessage());

}


// La sortie de session passe par le contrôleur public, même pour un administrateur.
$isLogoutPost = (($_GET['pages'] ?? '') === 'logout'
    && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST');
$isAdminPage = (($_GET['pages'] ?? '') === 'admin');
$isAdmin = isset($_SESSION['user_role_id']) && (int) $_SESSION['user_role_id'] === 1;

if ($isLogoutPost) {
    require_once "../controller/publicController.php";
} elseif ($isAdminPage && $isAdmin) {
    // Seul un compte de rôle 1 peut atteindre le contrôleur privé.
    require_once "../controller/privateController.php";
} else {
    // Les pages publiques restent accessibles aux visiteurs, utilisateurs et administrateurs.
    require_once "../controller/publicController.php";
}
