<!--
    MODAL DES MENTIONS LÉGALES (liens du footer)
    Fichier à inclure UNE SEULE FOIS (il est inclus à la fin de footer.php,
    donc présent sur toutes les pages qui affichent le footer).

    Principe (même logique que authModal.php) :
      - UN seul modal (#legalModal) réutilisé pour les 5 liens du footer.
      - Chaque lien du footer porte un attribut data-legal-open="<clé>".
      - À l'intérieur, un panneau par document : <section data-legal-panel="<clé>">.
        Le JS (scripts.js, bloc "MODAL MENTIONS LÉGALES") ouvre le modal,
        affiche le bon panneau et met à jour le titre.

    Les 5 clés : privacy, cookies, gdpr, data, terms.

    /!\ IMPORTANT : ces textes sont des MODÈLES pour le projet d'école
        (chocolaterie fictive). Ils doivent être relus/adaptés avant une vraie
        mise en ligne — ce ne sont pas des textes juridiques validés.
-->
<div id="legalModal" class="legal-modal" aria-hidden="true">

    <!-- Fond sombre : un clic dessus ferme le modal -->
    <div class="legal-modal__overlay" data-legal-close></div>

    <!-- Fenêtre -->
    <div class="legal-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="legalModalTitle">

        <!-- Bouton de fermeture -->
        <button type="button" class="legal-modal__close" aria-label="Fermer" data-legal-close>&times;</button>

        <!-- Titre : mis à jour par le JS selon le lien cliqué -->
        <h2 id="legalModalTitle" class="legal-modal__title">Mentions légales</h2>
        <span class="legal-modal__divider" aria-hidden="true"></span>

        <!-- Zone de contenu : un panneau par document, masqués sauf l'actif -->
        <div class="legal-modal__body">

            <!-- 1. POLITIQUE DE CONFIDENTIALITÉ -->
            <section class="legal-modal__panel" data-legal-panel="privacy" data-legal-title="Politique de confidentialité">
                <p>La présente politique explique comment Maison Rosalie collecte et protège vos données personnelles lorsque vous utilisez notre site.</p>
                <h3>Données que nous collectons</h3>
                <p>Nous collectons les informations que vous nous transmettez : nom, adresse email et message via le formulaire de contact, ainsi que les données de votre compte si vous en créez un.</p>
                <h3>Utilisation des données</h3>
                <p>Vos données servent uniquement à répondre à vos demandes, gérer votre compte et améliorer nos recettes et services. Elles ne sont jamais revendues à des tiers.</p>
                <h3>Durée de conservation</h3>
                <p>Vos données sont conservées le temps nécessaire à la finalité pour laquelle elles ont été collectées, puis supprimées.</p>
                <h3>Vos droits</h3>
                <p>Vous pouvez à tout moment demander l'accès, la rectification ou la suppression de vos données en nous écrivant à <a href="mailto:admin@maisonRosalie.be">admin@maisonRosalie.be</a>.</p>
            </section>

            <!-- 2. POLITIQUE DES COOKIES -->
            <section class="legal-modal__panel" data-legal-panel="cookies" data-legal-title="Politique des cookies">
                <p>Un cookie est un petit fichier déposé sur votre appareil pour permettre au site de fonctionner correctement et de mémoriser vos préférences.</p>
                <h3>Cookies que nous utilisons</h3>
                <ul>
                    <li><strong>Cookies nécessaires</strong> : indispensables au fonctionnement du site (session de connexion, panier). Ils ne peuvent pas être désactivés.</li>
                    <li><strong>Cookies de préférence</strong> : mémorisent par exemple votre email lorsque vous cochez « Se souvenir de moi ».</li>
                    <li><strong>Cookies de mesure d'audience</strong> : nous aident à comprendre comment le site est utilisé (de façon anonyme).</li>
                </ul>
                <h3>Gérer les cookies</h3>
                <p>Vous pouvez à tout moment configurer votre navigateur pour refuser ou supprimer les cookies. Certaines fonctionnalités du site peuvent alors ne plus être disponibles.</p>
            </section>

            <!-- 3. DROITS GDPR (RGPD) -->
            <section class="legal-modal__panel" data-legal-panel="gdpr" data-legal-title="Droits GDPR">
                <p>Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez des droits suivants sur vos données personnelles :</p>
                <ul>
                    <li><strong>Droit d'accès</strong> : savoir quelles données nous détenons sur vous.</li>
                    <li><strong>Droit de rectification</strong> : corriger des données inexactes.</li>
                    <li><strong>Droit à l'effacement</strong> : demander la suppression de vos données.</li>
                    <li><strong>Droit à la limitation</strong> : restreindre le traitement de vos données.</li>
                    <li><strong>Droit à la portabilité</strong> : récupérer vos données dans un format réutilisable.</li>
                    <li><strong>Droit d'opposition</strong> : vous opposer à certains traitements.</li>
                </ul>
                <h3>Exercer vos droits</h3>
                <p>Pour exercer l'un de ces droits, écrivez-nous à <a href="mailto:admin@maisonRosalie.be">admin@maisonRosalie.be</a>. Vous pouvez également introduire une réclamation auprès de l'Autorité de protection des données.</p>
            </section>

            <!-- 4. TRAITEMENT DES DONNÉES -->
            <section class="legal-modal__panel" data-legal-panel="data" data-legal-title="Traitement des données">
                <p>Cette page détaille la manière dont vos données sont traitées au sein de Maison Rosalie.</p>
                <h3>Responsable du traitement</h3>
                <p>Maison Rosalie est responsable du traitement de vos données personnelles collectées sur ce site.</p>
                <h3>Base légale</h3>
                <p>Nous traitons vos données sur la base de votre consentement, de l'exécution d'un contrat (gestion de votre compte) et de notre intérêt légitime à améliorer nos services.</p>
                <h3>Sécurité</h3>
                <p>Les mots de passe sont stockés de façon chiffrée et l'accès aux données est strictement limité. Nous mettons en œuvre des mesures techniques pour protéger vos informations contre tout accès non autorisé.</p>
                <h3>Sous-traitants</h3>
                <p>Certaines données peuvent être traitées par des prestataires techniques (hébergement), toujours dans le respect du RGPD.</p>
            </section>

            <!-- 5. CONDITIONS D'UTILISATION -->
            <section class="legal-modal__panel" data-legal-panel="terms" data-legal-title="Conditions d'utilisation">
                <p>En utilisant le site Maison Rosalie, vous acceptez les présentes conditions d'utilisation.</p>
                <h3>Utilisation du site</h3>
                <p>Le site propose des recettes, des contenus et la possibilité de créer un compte. Vous vous engagez à utiliser le site de manière loyale et à ne pas nuire à son bon fonctionnement.</p>
                <h3>Comptes utilisateurs</h3>
                <p>Vous êtes responsable de la confidentialité de vos identifiants et des activités réalisées depuis votre compte.</p>
                <h3>Propriété intellectuelle</h3>
                <p>Les textes, recettes, logos et images du site sont la propriété de Maison Rosalie et ne peuvent être reproduits sans autorisation.</p>
                <h3>Responsabilité</h3>
                <p>Les recettes sont fournies à titre indicatif. Maison Rosalie ne saurait être tenue responsable d'un usage inapproprié des contenus du site.</p>
            </section>

        </div>
    </div>
</div>
