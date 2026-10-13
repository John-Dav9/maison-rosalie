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
<?php $authSuccess = is_array($authFeedback ?? null) && ($authFeedback['type'] ?? '') === 'success'; ?>
<div id="authModal" class="auth-modal" aria-hidden="true" data-auth-modal-auto-open="<?= !empty($recipeAutoOpenAuthModal) ? 'true' : 'false' ?>" data-auth-success="<?= $authSuccess ? 'true' : 'false' ?>">

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
        <?php if ($authSuccess): ?>
            <p class="mb-4 rounded border border-green-700 bg-green-50 p-3 text-sm text-green-900" role="status" aria-live="polite">
                <?= htmlspecialchars($authFeedback['message'], ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php else: ?>
            <?php if (is_array($authFeedback ?? null) && isset($authFeedback['message']) && is_string($authFeedback['message'])): ?>
                <!-- Le contrôleur indique le résultat de la connexion ou de la création du compte. -->
                <p class="mb-4 rounded border border-red-700 bg-red-50 p-3 text-sm text-red-900" role="alert" aria-live="polite">
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
                    <div class="auth-modal__password">
                        <input type="password" id="loginPassword" name="password" placeholder="••••••••" autocomplete="current-password">
                        <button type="button" class="auth-modal__eye" data-auth-eye aria-label="Afficher le mot de passe" aria-pressed="false"><i class="fa-regular fa-eye-slash" aria-hidden="true"></i></button>
                    </div>
                    <span class="auth-modal__error" data-error-for="password"></span>
                </div>
                <label class="auth-modal__remember"><input type="checkbox" name="remember" value="1" data-auth-remember><span>Se souvenir de moi</span></label>
                <button type="submit" class="auth-modal__submit">Se connecter</button>
                <p class="auth-modal__switch">Pas encore de compte ? <button type="button" class="auth-modal__link" data-tab="register">Créer un compte</button></p>
            </form>

            <!-- Panneau INSCRIPTION -->
            <form class="auth-modal__panel <?= $authActiveTab === 'register' ? 'is-active' : '' ?>" data-panel="register" data-auth-form="register"
                  action="./?pages=register" method="post" novalidate>
                <input type="hidden" name="return_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/?pages=home', ENT_QUOTES, 'UTF-8') ?>">
                <div class="auth-modal__field"><label for="registerUsername">Nom d'utilisateur</label><input type="text" id="registerUsername" name="username" placeholder="Votre pseudo" autocomplete="username"><span class="auth-modal__error" data-error-for="username"></span></div>
                <div class="auth-modal__field"><label for="registerEmail">Adresse email</label><input type="email" id="registerEmail" name="email" placeholder="vous@exemple.be" autocomplete="email"><span class="auth-modal__error" data-error-for="email"></span></div>
                <div class="auth-modal__field"><label for="registerPassword">Mot de passe</label><div class="auth-modal__password"><input type="password" id="registerPassword" name="password" placeholder="••••••••" autocomplete="new-password"><button type="button" class="auth-modal__eye" data-auth-eye aria-label="Afficher le mot de passe" aria-pressed="false"><i class="fa-regular fa-eye-slash" aria-hidden="true"></i></button></div><span class="auth-modal__error" data-error-for="password"></span></div>
                <div class="auth-modal__field"><label for="registerPasswordConfirm">Confirmer le mot de passe</label><div class="auth-modal__password"><input type="password" id="registerPasswordConfirm" name="password_confirm" placeholder="••••••••" autocomplete="new-password"><button type="button" class="auth-modal__eye" data-auth-eye aria-label="Afficher le mot de passe" aria-pressed="false"><i class="fa-regular fa-eye-slash" aria-hidden="true"></i></button></div><span class="auth-modal__error" data-error-for="password_confirm"></span></div>
    
            <!-- ACCEPTATION DES CONDITIONS (obligatoire)
                 Sans cette case cochée, la validation JS bloque l'inscription.
                 Les liens ouvrent le modal des mentions légales (data-legal-open).
                 /!\ IMPORTANT (back-end) : à REVÉRIFIER côté PHP — refuser la
                 création du compte si $_POST['accept_terms'] n'est pas présent
                 (la validation JS ne suffit pas, elle peut être contournée). -->
            <div class="auth-modal__field">
                <label class="auth-modal__remember auth-modal__terms">
                    <input type="checkbox" name="accept_terms" value="1" data-auth-terms>
                    <span>J'accepte les
                        <button type="button" class="auth-modal__link" data-legal-open="terms">conditions d'utilisation</button>
                        et la
                        <button type="button" class="auth-modal__link" data-legal-open="privacy">politique de confidentialité</button>.
                    </span>
                </label>
                <span class="auth-modal__error" data-error-for="accept_terms"></span>
            </div>

            <button type="submit" class="auth-modal__submit">Créer mon compte</button>
                <p class="auth-modal__switch">Déjà inscrit ? <button type="button" class="auth-modal__link" data-tab="login">Se connecter</button></p>
            </form>
        <?php endif; ?>

    </div>
</div>