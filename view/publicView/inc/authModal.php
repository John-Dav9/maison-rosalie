<!--
    MODAL CONNEXION / INSCRIPTION (FE-50)
    Fichier à inclure (une seule fois) dans la page, par exemple à la fin
    de menuPublicView.php. Le bouton "Connexion" de la navbar l'ouvre via JS
    (voir scripts.js, bloc "MODAL CONNEXION / INSCRIPTION").

    Deux onglets dans le même modal :
      - Connexion  (.auth-modal__panel[data-panel="login"])
      - Inscription (.auth-modal__panel[data-panel="register"])
    La bascule entre les deux est gérée en JS (classe .is-active).

    ŒIL "AFFICHER / MASQUER LE MOT DE PASSE" :
      Chaque champ mot de passe est entouré d'un <div class="auth-modal__password">
      qui contient l'input + un bouton œil (.auth-modal__eye).
      Par défaut l'œil est BARRÉ (fa-eye-slash) et le mot de passe est masqué.
      Au clic, le JS bascule l'input en type="text" et montre l'œil ouvert
      (fa-eye). Voir scripts.js, bloc "ŒIL AFFICHER / MASQUER LE MOT DE PASSE".
      (Les icônes viennent de Font Awesome, déjà chargé dans menuPublicView.php.)
-->
<div id="authModal" class="auth-modal" aria-hidden="true" data-auth-modal-auto-open="<?= !empty($recipeAutoOpenAuthModal) ? 'true' : 'false' ?>">

    <!-- Fond sombre semi-transparent : un clic dessus ferme le modal -->
    <div class="auth-modal__overlay" data-auth-close></div>

    <!-- Fenêtre -->
    <div class="auth-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="authModalTitle">

        <!-- Bouton de fermeture -->
        <button type="button" class="auth-modal__close" aria-label="Fermer" data-auth-close>&times;</button>

        <!-- En-tête : logo + titre -->
        <div class="auth-modal__header">
            <img src="./assets/logo/logo-text-only.svg" alt="Maison Rosalie" class="auth-modal__logo">
            <h2 id="authModalTitle" class="auth-modal__title">Bienvenue</h2>
        </div>

        <?php $authActiveTab = ($authFeedback['form'] ?? '') === 'register' ? 'register' : 'login'; ?>
        <?php if (is_array($authFeedback ?? null) && isset($authFeedback['message']) && is_string($authFeedback['message'])): ?>
            <?php $feedbackIsSuccess = ($authFeedback['type'] ?? '') === 'success'; ?>
            <!-- Le contrôleur indique le résultat de la connexion ou de la création du compte. -->
            <p class="mb-4 rounded border p-3 text-sm <?= $feedbackIsSuccess ? 'border-green-700 bg-green-50 text-green-900' : 'border-red-700 bg-red-50 text-red-900' ?>"
               role="<?= $feedbackIsSuccess ? 'status' : 'alert' ?>">
                <?= htmlspecialchars($authFeedback['message'], ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <!-- Onglets -->
        <div class="auth-modal__tabs" role="tablist">
            <button type="button" class="auth-modal__tab <?= $authActiveTab === 'login' ? 'is-active' : '' ?>" data-tab="login" role="tab">Connexion</button>
            <button type="button" class="auth-modal__tab <?= $authActiveTab === 'register' ? 'is-active' : '' ?>" data-tab="register" role="tab">Inscription</button>
        </div>

        <!-- Panneau CONNEXION -->
        <!-- data-auth-form : identifie le formulaire pour la validation JS (scripts.js) -->
        <form class="auth-modal__panel <?= $authActiveTab === 'login' ? 'is-active' : '' ?>" data-panel="login" data-auth-form="login"
              action="./?pages=login" method="post" novalidate>
            <!-- Permet au contrôleur de revenir à la page qui affichait le modal. -->
            <input type="hidden" name="return_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/?pages=home', ENT_QUOTES, 'UTF-8') ?>">
            <div class="auth-modal__field">
                <label for="loginEmail">Adresse email</label>
                <input type="email" id="loginEmail" name="email" placeholder="vous@exemple.be" autocomplete="email">
                <span class="auth-modal__error" data-error-for="email"></span>
            </div>
            <div class="auth-modal__field">
                <label for="loginPassword">Mot de passe</label>
                <!-- Enveloppe input + œil : positionne le bouton œil par-dessus la droite du champ.
                     L'input reste le 1er enfant (le JS lira .querySelector('input')). -->
                <div class="auth-modal__password">
                    <input type="password" id="loginPassword" name="password" placeholder="••••••••" autocomplete="current-password">
                    <!-- Bouton œil : barré par défaut (mot de passe masqué). data-auth-eye = cible pour le JS -->
                    <button type="button" class="auth-modal__eye" data-auth-eye aria-label="Afficher le mot de passe" aria-pressed="false">
                        <i class="fa-regular fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <span class="auth-modal__error" data-error-for="password"></span>
            </div>

            <!-- "SE SOUVENIR DE MOI"
                 La case envoie remember=1 au serveur quand elle est cochée.
                 /!\ IMPORTANT (back-end) : garder l'utilisateur connecté après
                 fermeture du navigateur se fait CÔTÉ PHP. Si $_POST['remember']
                 est présent, il faut prolonger la durée de vie du cookie de
                 session (ou poser un cookie "remember token" stocké en BDD) ;
                 sinon la session expire normalement. Le front ne peut pas le
                 garantir seul. Ici, le JS mémorise juste l'email pour le
                 pré-remplir au retour (jamais le mot de passe). -->
            <label class="auth-modal__remember">
                <input type="checkbox" name="remember" value="1" data-auth-remember>
                <span>Se souvenir de moi</span>
            </label>

            <button type="submit" class="auth-modal__submit">Se connecter</button>
            <p class="auth-modal__switch">
                Pas encore de compte ?
                <button type="button" class="auth-modal__link" data-tab="register">Créer un compte</button>
            </p>
        </form>

        <!-- Panneau INSCRIPTION -->
        <form class="auth-modal__panel <?= $authActiveTab === 'register' ? 'is-active' : '' ?>" data-panel="register" data-auth-form="register"
              action="./?pages=register" method="post" novalidate>
            <!-- La destination est réutilisée après le traitement POST d'inscription. -->
            <input type="hidden" name="return_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/?pages=home', ENT_QUOTES, 'UTF-8') ?>">
            <div class="auth-modal__field">
                <label for="registerUsername">Nom d'utilisateur</label>
                <input type="text" id="registerUsername" name="username" placeholder="Votre pseudo" autocomplete="username">
                <span class="auth-modal__error" data-error-for="username"></span>
            </div>
            <div class="auth-modal__field">
                <label for="registerEmail">Adresse email</label>
                <input type="email" id="registerEmail" name="email" placeholder="vous@exemple.be" autocomplete="email">
                <span class="auth-modal__error" data-error-for="email"></span>
            </div>
            <div class="auth-modal__field">
                <label for="registerPassword">Mot de passe</label>
                <!-- Enveloppe input + œil -->
                <div class="auth-modal__password">
                    <input type="password" id="registerPassword" name="password" placeholder="••••••••" autocomplete="new-password">
                    <button type="button" class="auth-modal__eye" data-auth-eye aria-label="Afficher le mot de passe" aria-pressed="false">
                        <i class="fa-regular fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <span class="auth-modal__error" data-error-for="password"></span>
            </div>
            <div class="auth-modal__field">
                <label for="registerPasswordConfirm">Confirmer le mot de passe</label>
                <!-- Enveloppe input + œil -->
                <div class="auth-modal__password">
                    <input type="password" id="registerPasswordConfirm" name="password_confirm" placeholder="••••••••" autocomplete="new-password">
                    <button type="button" class="auth-modal__eye" data-auth-eye aria-label="Afficher le mot de passe" aria-pressed="false">
                        <i class="fa-regular fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <span class="auth-modal__error" data-error-for="password_confirm"></span>
            </div>
            <button type="submit" class="auth-modal__submit">Créer mon compte</button>
            <p class="auth-modal__switch">
                Déjà inscrit ?
                <button type="button" class="auth-modal__link" data-tab="login">Se connecter</button>
            </p>
        </form>

    </div>
</div>