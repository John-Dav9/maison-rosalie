<?php
# 1 ) Ce fichier est le point d'entrée de l'application, il est appelé par le serveur web (Apache, Nginx, IIS, etc.) et il va charger les fichiers nécessaires pour exécuter l'application. - Comment l'appelle-t-on ? 

# 2) que lance-t-on à cette ligne ?
session_start();

# 3) que charge-t-on à cette ligne ?
require_once "../config.php";
# 4) que charge-t-on à ces lignes ?
require_once "../model/PostModel.php";# table post
require_once "../model/CategoryModel.php";# table category
require_once "../model/UserModel.php";# table user


# 5 ) Nous essayons delancer quelle type d'objet, et pourquoi?
try {
    $connectPDO = new PDO(
        DB_TYPE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset='.DB_CHARSET,
        DB_LOGIN,
        DB_PWD
    );
        # 6 ) activation de quoi
        $connectPDO->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        # 7 ) En quoi voulons-nous que les résultats soient retournés par défaut ? 
        $connectPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

    
# 8 ) le catch est appelé dans quel cas
}catch(Exception $e){
    # 9 ) et que fait-il?
    die($e->getMessage());

}


# Router

// ici sont redirigés les administrateurs connectés.
if(isset($_SESSION['myID'])&&$_SESSION['myID']==session_id()){
    require_once "../controller/privateController.php";
  
// zone publique, pour les visiteurs
}else{
    require_once "../controller/publicController.php";
}


# 10 )
$connectPDO = null;?>
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
<?php require_once "./../view/publicView/inc/header.php" ?>

<?php require_once "./../view/publicView/inc/footer.php" ?>
<script src="./js/scripts.js"></script>
</body>
</html>
