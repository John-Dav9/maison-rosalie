<?php
# public/index.php 

/****************************
 * Chargement des dépendances
 * ici seulement config.php
 * qui se trouve 1 niveau en
 * dessous
 ****************************/
require_once '../config.php';

// Reconstruit une destination locale et ne conserve que les paramètres simples d'une page autorisée.
$resolveAuthReturnUrl = static function (): string {
    $returnTo = $_POST['return_to'] ?? null;
    if (!is_string($returnTo)) {
        return './?pages=home';
    }

    $returnParts = parse_url($returnTo);
    $returnPath = is_array($returnParts) ? ($returnParts['path'] ?? '') : '';
    if (!is_array($returnParts)
        || isset($returnParts['scheme'])
        || isset($returnParts['host'])
        || $returnPath === ''
        || $returnPath[0] !== '/'
        || substr($returnPath, 0, 2) === '//') {
        return './?pages=home';
    }

    $returnQuery = [];
    parse_str($returnParts['query'] ?? '', $returnQuery);
    $returnPage = $returnQuery['pages'] ?? 'home';
    if (!is_string($returnPage) || !in_array($returnPage, VALID_PAGES, true)) {
        return './?pages=home';
    }

    $safeQuery = ['pages' => $returnPage];
    foreach ($returnQuery as $key => $value) {
        if ($key !== 'pages' && is_string($key)
            && (is_string($value) || is_numeric($value))) {
            $safeQuery[$key] = $value;
        }
    }

    return './?' . http_build_query($safeQuery);
};

// Traite la déconnexion en POST puis revient à la page affichée avant l'action.
if (($_GET['pages'] ?? '') === 'logout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Le manager supprime les données et le cookie de session de l'utilisateur.
    require_once BASE_URL . '/model/ManagerInterface.php';
    require_once BASE_URL . '/model/UserInterface.php';
    require_once BASE_URL . '/model/AbstractMapping.php';
    require_once BASE_URL . '/model/mapping/userMapping.php';
    require_once BASE_URL . '/model/manager/UserManager.php';

    $userManager = new \model\manager\UserManager($connectPDO);
    $userManager->disconnect();

    header('Location: ' . $resolveAuthReturnUrl());
    exit;
}

// Traite la création de compte avant le routage public, puisque "register" est une route POST.
if (($_GET['pages'] ?? '') === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Charge les mêmes dépendances que pour la connexion; aucun autoloader n'est configuré.
    require_once BASE_URL . '/model/ManagerInterface.php';
    require_once BASE_URL . '/model/UserInterface.php';
    require_once BASE_URL . '/model/AbstractMapping.php';
    require_once BASE_URL . '/model/mapping/userMapping.php';
    require_once BASE_URL . '/model/manager/UserManager.php';

    $postedName = $_POST['username'] ?? null;
    $postedEmail = $_POST['email'] ?? null;
    $postedPassword = $_POST['password'] ?? null;
    $postedPasswordConfirm = $_POST['password_confirm'] ?? null;
    $registrationError = null;

    if (!is_string($postedName) || !is_string($postedEmail)
        || !is_string($postedPassword) || !is_string($postedPasswordConfirm)) {
        $registrationError = 'Veuillez remplir tous les champs du formulaire.';
    } elseif (trim($postedName) === '' || strlen(trim($postedName)) > 100) {
        $registrationError = 'Le nom est obligatoire et ne doit pas dépasser 100 caractères.';
    } elseif (!filter_var(trim($postedEmail), FILTER_VALIDATE_EMAIL)) {
        $registrationError = 'Veuillez saisir une adresse email valide.';
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $postedPassword)) {
        $registrationError = 'Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.';
    } elseif ($postedPassword !== $postedPasswordConfirm) {
        $registrationError = 'Les mots de passe ne correspondent pas.';
    }

    $registrationSuccess = false;
    if ($registrationError === null) {
        $userManager = new \model\manager\UserManager($connectPDO);
        try {
            $registeredUser = $userManager->register([
                'user_real_name' => trim($postedName),
                'user_mail' => trim($postedEmail),
                // Le mot de passe et sa confirmation sont transmis sans trim.
                'user_pwd' => $postedPassword,
                'user_pwd_confirm' => $postedPasswordConfirm,
            ]);

            if ($registeredUser !== false) {
                // Connecte le nouveau compte afin d'activer immédiatement ses fonctionnalités.
                $registrationSuccess = $userManager->connect([
                    'user_mail' => trim($postedEmail),
                    'user_pwd' => $postedPassword,
                ]);
                if (!$registrationSuccess) {
                    $registrationError = 'Le compte a été créé, mais la connexion automatique a échoué.';
                }
            } else {
                $registrationError = 'La création du compte a échoué. Vérifiez les informations et réessayez.';
            }
        } catch (\InvalidArgumentException $e) {
            // Le manager utilise cette exception notamment quand l'adresse existe déjà.
            $registrationError = $e->getMessage();
        } catch (\Throwable $e) {
            error_log('Échec du traitement de l’inscription : ' . $e->getMessage());
            $registrationError = 'Une erreur est survenue pendant la création du compte.';
        }
    }

    $_SESSION['auth_feedback'] = [
        'type' => $registrationSuccess ? 'success' : 'error',
        'form' => 'register',
        'message' => $registrationSuccess
            ? 'Compte créé et connexion réussie.'
            : ($registrationError ?? 'La création du compte a échoué.'),
    ];

    header('Location: ' . $resolveAuthReturnUrl());
    exit;
}

// Le formulaire du modal transmet ses champs "email" et "password" à cette route.
if (($_GET['pages'] ?? '') === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Charge les classes nécessaires au manager utilisateur, le projet n'ayant pas d'autoloader.
    require_once BASE_URL . '/model/ManagerInterface.php';
    require_once BASE_URL . '/model/UserInterface.php';
    require_once BASE_URL . '/model/AbstractMapping.php';
    require_once BASE_URL . '/model/mapping/userMapping.php';
    require_once BASE_URL . '/model/manager/UserManager.php';

    $postedEmail = $_POST['email'] ?? null;
    $postedPassword = $_POST['password'] ?? null;
    $connected = false;

    if (is_string($postedEmail) && is_string($postedPassword)
        && trim($postedEmail) !== '' && $postedPassword !== '') {
        // Adapte les noms du modal aux clés attendues par UserManager::connect().
        $userManager = new \model\manager\UserManager($connectPDO);
        $connected = $userManager->connect([
            'user_mail' => trim($postedEmail),
            // Le mot de passe reste inchangé : les espaces éventuels en font partie.
            'user_pwd' => $postedPassword,
        ]);
    }

    // Le message sera affiché dans le modal après la redirection (POST/Redirect/GET).
    $_SESSION['auth_feedback'] = [
        'type' => $connected ? 'success' : 'error',
        'form' => 'login',
        'message' => $connected
            ? 'Connexion réussie.'
            : 'Adresse email ou mot de passe incorrect.',
    ];

    // Revient à la page d'origine sans accepter de redirection vers un site externe.
    header('Location: ' . $resolveAuthReturnUrl());
    exit;
}

// Enregistre un message de contact et envoie sa notification en texte brut.
if (($_GET['pages'] ?? '') === 'contact' && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    require_once BASE_URL . '/model/ManagerInterface.php';
    require_once BASE_URL . '/model/AbstractMapping.php';
    require_once BASE_URL . '/model/mapping/contactMapping.php';
    require_once BASE_URL . '/model/ContactText.php';
    require_once BASE_URL . '/model/manager/ContactManager.php';
    require_once BASE_URL . '/model/manager/ContactMailer.php';

    $contactError = null;
    $postedToken = $_POST['csrf_token'] ?? null;
    $sessionToken = $_SESSION['contact_csrf_token'] ?? '';
    if (!is_string($postedToken) || !is_string($sessionToken) || $sessionToken === '' || !hash_equals($sessionToken, $postedToken)) {
        $contactError = 'La session du formulaire a expiré. Rechargez la page puis réessayez.';
    } elseif (!is_string($_POST['website'] ?? '') || trim((string) ($_POST['website'] ?? '')) !== '') {
        $contactError = 'Le formulaire n’a pas pu être validé.';
    } elseif (isset($_SESSION['contact_last_submitted_at'])
        && is_int($_SESSION['contact_last_submitted_at'])
        && time() - $_SESSION['contact_last_submitted_at'] < 30) {
        $contactError = 'Veuillez attendre quelques secondes avant d’envoyer un autre message.';
    } else {
        $contactManager = new \model\manager\ContactManager($connectPDO);
        $savedContact = $contactManager->addContact($_POST);
        if ($savedContact === false) {
            $contactError = 'Vérifiez les champs du formulaire et réessayez.';
        } else {
            $_SESSION['contact_last_submitted_at'] = time();
            $recipient = defined('CONTACT_RECIPIENT_EMAIL')
                ? CONTACT_RECIPIENT_EMAIL
                : 'maison.rosalie.chocolat@gmail.com';
            $mailer = new \model\manager\ContactMailer();
            $mailSent = $mailer->send([
                'name' => $savedContact->getName(),
                'email' => $savedContact->getEmail(),
                'subject' => $savedContact->getSubject() ?? '',
                'message' => $savedContact->getMessage(),
            ], $recipient);
            $contactError = $mailSent
                ? null
                : 'Votre message est enregistré, mais la notification par courriel n’a pas pu être envoyée.';
        }
    }

    $_SESSION['contact_feedback'] = [
        'type' => $contactError === null ? 'success' : 'error',
        'message' => $contactError ?? 'Votre message a été enregistré et envoyé.',
    ];
    header('Location: ./?pages=contact');
    exit;
}

// Récupère le message temporaire après la redirection, puis le retire de la session.
$authFeedback = $_SESSION['auth_feedback'] ?? null;
unset($_SESSION['auth_feedback']);
$contactFeedback = $_SESSION['contact_feedback'] ?? null;
unset($_SESSION['contact_feedback']);
if (!isset($_SESSION['contact_csrf_token']) || !is_string($_SESSION['contact_csrf_token'])) {
    $_SESSION['contact_csrf_token'] = bin2hex(random_bytes(32));
}
$contactCsrfToken = $_SESSION['contact_csrf_token'];

// si non existence de la variable
// $_GET nommée 'p'
if (!isset($_GET['pages'])) {

    // Nous sommes sur l'accueil
    // chargement de view/homepage.php
    include BASE_URL . "/view/publicView/home.php";

    // sinon si la variable get 'pages' est dans le
    // tableau VALID_PAGES    
} elseif (in_array($_GET['pages'], VALID_PAGES)) {
    // La route recette charge ses dépendances et ses données avant d'inclure la vue.
    if ($_GET['pages'] === 'recipe') {
        require_once BASE_URL . '/model/ManagerInterface.php';
        require_once BASE_URL . '/model/AbstractMapping.php';
        require_once BASE_URL . '/model/StringTrait.php';
        require_once BASE_URL . '/model/mapping/userMapping.php';
        require_once BASE_URL . '/model/mapping/recetteMapping.php';
        require_once BASE_URL . '/model/mapping/categoryMapping.php';
        require_once BASE_URL . '/model/mapping/ingredientMapping.php';
        require_once BASE_URL . '/model/mapping/stepsMapping.php';
        require_once BASE_URL . '/model/manager/RecetteManager.php';

        // On n'accepte que des slugs minuscules alphanumériques séparés par des tirets.
        $recipeSlug = $_GET['slug'] ?? null;
        $recipeDetails = null;
        if (is_string($recipeSlug) && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $recipeSlug)) {
            $recipeManager = new \model\manager\RecetteManager($connectPDO);
            $recipeDetails = $recipeManager->getRecetteDetailsBySlug($recipeSlug);
        }

        if ($recipeDetails === null) {
            http_response_code(404);
            include BASE_URL . '/view/publicView/404View.php';
        } else {
            $recipe = $recipeDetails['recette'];
            $recipeIngredients = $recipeDetails['ingredients'];
            $recipeSteps = $recipeDetails['etapes'];
            include BASE_URL . '/view/publicView/recipe.php';
        }
    } elseif ($_GET['pages'] === 'recettes') {
        // Charge les dépendances du modèle et récupère les données du catalogue.
        require_once BASE_URL . '/model/ManagerInterface.php';
        require_once BASE_URL . '/model/AbstractMapping.php';
        require_once BASE_URL . '/model/StringTrait.php';
        require_once BASE_URL . '/model/mapping/userMapping.php';
        require_once BASE_URL . '/model/mapping/recetteMapping.php';
        require_once BASE_URL . '/model/mapping/categoryMapping.php';
        require_once BASE_URL . '/model/mapping/ingredientMapping.php';
        require_once BASE_URL . '/model/mapping/stepsMapping.php';
        require_once BASE_URL . '/model/mapping/noteMapping.php';
        require_once BASE_URL . '/model/manager/RecetteManager.php';
        require_once BASE_URL . '/model/manager/NoteManager.php';

        $recipeManager = new \model\manager\RecetteManager($connectPDO);
        $recettes = $recipeManager->getAllRecettes();
        $categories = $recipeManager->getAllCategories();

        // Les catégories de chaque carte viennent des associations enregistrées.
        foreach ($recettes as $recette) {
            $recette->setCategories($recipeManager->getCategoriesByRecetteId((int) $recette->getRecetteId()));
        }

        $recipeIds = array_map(static fn($recette): int => (int) $recette->getRecetteId(), $recettes);
        $recipeRatingStats = (new \model\manager\NoteManager($connectPDO))
            ->getRatingSummariesByRecipeIds($recipeIds);

        $difficultes = array_values(array_unique(array_filter(array_map(
            static fn($recette): ?string => $recette->getRecetteDifficulty(),
            $recettes
        ))));
        sort($difficultes, SORT_NATURAL | SORT_FLAG_CASE);

        include BASE_URL . '/view/publicView/recettes.php';
    } else {
        // Les autres vues publiques gardent leur routage existant.
        include BASE_URL . "/view/publicView/" . $_GET['pages'] . ".php";
    }

    // sinon (pages non valide)
} else {
    include BASE_URL . "/view/publicView/404view.php";
}
