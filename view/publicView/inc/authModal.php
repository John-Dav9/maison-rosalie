<!--
    MODAL CONNEXION / INSCRIPTION (FE-50)
    Fichier à inclure (une seule fois) dans la page, par exemple à la fin
    de menuPublicView.php. Le bouton "Connexion" de la navbar l'ouvre via JS
    (voir scripts.js, bloc "MODAL CONNEXION / INSCRIPTION").

    Deux onglets dans le même modal :
      - Connexion  (.auth-modal__panel[data-panel="login"])
      - Inscription (.auth-modal__panel[data-panel="register"])
    La bascule entre les deux est gérée en JS (classe .is-active).

    Pas de validation pour l'instant (à ajouter plus tard, comme le contact).
-->
<div id="authModal" class="auth-modal" aria-hidden="true">

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

        <!-- Onglets -->
        <div class="auth-modal__tabs" role="tablist">
            <button type="button" class="auth-modal__tab is-active" data-tab="login" role="tab">Connexion</button>
            <button type="button" class="auth-modal__tab" data-tab="register" role="tab">Inscription</button>
        </div>

        <!-- Panneau CONNEXION -->
        <!-- data-auth-form : identifie le formulaire pour la validation JS (scripts.js) -->
        <form class="auth-modal__panel is-active" data-panel="login" data-auth-form="login"
              action="./?pages=login" method="post" novalidate>
            <div class="auth-modal__field">
                <label for="loginEmail">Adresse email</label>
                <input type="email" id="loginEmail" name="email" placeholder="vous@exemple.be" autocomplete="email">
                <span class="auth-modal__error" data-error-for="email"></span>
            </div>
            <div class="auth-modal__field">
                <label for="loginPassword">Mot de passe</label>
                <input type="password" id="loginPassword" name="password" placeholder="••••••••" autocomplete="current-password">
                <span class="auth-modal__error" data-error-for="password"></span>
            </div>
            <button type="submit" class="auth-modal__submit">Se connecter</button>
            <p class="auth-modal__switch">
                Pas encore de compte ?
                <button type="button" class="auth-modal__link" data-tab="register">Créer un compte</button>
            </p>
        </form>

        <!-- Panneau INSCRIPTION -->
        <form class="auth-modal__panel" data-panel="register" data-auth-form="register"
              action="./?pages=register" method="post" novalidate>
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
                <input type="password" id="registerPassword" name="password" placeholder="••••••••" autocomplete="new-password">
                <span class="auth-modal__error" data-error-for="password"></span>
            </div>
            <div class="auth-modal__field">
                <label for="registerPasswordConfirm">Confirmer le mot de passe</label>
                <input type="password" id="registerPasswordConfirm" name="password_confirm" placeholder="••••••••" autocomplete="new-password">
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