    <?php require_once "./../view/publicView/inc/menuPublicView.php" ?>
    <!--
    PAGE À PROPOS (FE-40)
    Structure (maquette du designer) :
    1. .about__intro   → titre "À propos" + texte de présentation, centrés
    2. .about__story   → 2 colonnes : photo de la boutique à gauche, "Notre Histoire" à droite
                         (empilées sur mobile)
    Les styles sont dans public/css/style.css, tous préfixés par #about.
-->
    <main id="about">

        <!-- 1. INTRODUCTION -->
        <section class="about__intro">
            <h1 class="about__title about__title--diamond-left">À propos</h1>
            <span class="about__divider"></span>

            <p class="about__lead">
                Nous sommes une équipe passionnée par la cuisine, les bons produits et les moments de partage.
                Notre établissement est un lieu où l'on se retrouve autour de plats simples et authentiques,
                préparés avec soin.
            </p>
        </section>

        <!-- 2. NOTRE HISTOIRE -->
        <section class="about__story">

            <!-- photo de la boutique : chargée seulement quand elle arrive à l'écran (FE-66) -->
            <figure class="about__media">
                <img src="./assets/images/Untitleaaad.jpg"
                    alt="Façade de la boutique Maison Rosalie avec sa terrasse et ses chaises en rotin"
                    width="600" height="620" loading="lazy">
            </figure>

            <div class="about__text">
                <h2 class="about__title about__title--small about__title--diamond-right">Notre Histoire</h2>
                <span class="about__divider"></span>

                <p class="about__tagline">L'élégance du chocolat belge depuis 1987</p>

                <p>
                    Inspirée par l'art de vivre belge et les grandes maisons européennes, Rosalie a imaginé
                    des chocolats conçus comme des bijoux gustatifs. Chaque création est une rencontre entre
                    la noblesse des matières premières, la précision artisanale et une élégance intemporelle.
                </p>

                <p>
                    Plus qu'une chocolaterie, Maison Rosalie est une maison où chaque détail célèbre l'art
                    du chocolat : la finesse, la délicatesse et le raffinement.
                </p>

                <p>
                    Rosalie a rencontré un tel succès qu'elle a voulu se lancer dans la création de recettes
                    à base de son chocolat, celui qu'elle utilise pour la création de ses délicieuses pralines.
                </p>

                <p>Vous découvrirez ici quelques merveilleuses recettes facilement réalisables.</p>

                <p>Bonne dégustation.</p>
            </div>

        </section>

        <!-- 3. NOS VALEURS : 4 cartes (1 colonne sur mobile, 2 sur tablette, 4 sur ordinateur) -->
        <section class="about__section" aria-labelledby="values-title">
            <div class="about__heading">
                <h2 id="values-title" class="about__title about__title--small">Nos valeurs</h2>
                <span class="about__divider"></span>
            </div>

            <ul class="about__values">
                <li class="about__value">
                    <h3>L'artisanat</h3>
                    <p>Chaque chocolat est tempéré, moulé et décoré à la main dans notre atelier liégeois.</p>
                </li>
                <li class="about__value">
                    <h3>La transmission</h3>
                    <p>Nos recettes passent de génération en génération, sur les cahiers écrits à la plume de Rosalie.</p>
                </li>
                <li class="about__value">
                    <h3>Des fèves choisies</h3>
                    <p>Nous sélectionnons nos fèves de cacao auprès de petites coopératives, pour leur goût et leur origine.</p>
                </li>
                <li class="about__value">
                    <h3>Le partage</h3>
                    <p>Un bon chocolat se déguste à plusieurs : c'est pour cela que nous ouvrons aujourd'hui notre livre de recettes.</p>
                </li>
            </ul>
        </section>

        <!-- 4. CHIFFRES CLÉS
         Les vraies valeurs sont écrites dans le HTML : la page reste juste sans JavaScript.
         public/js/about.js les fait simplement défiler de 0 jusqu'à data-count quand elles arrivent à l'écran. -->
        <section class="about__section" aria-labelledby="stats-title">
            <div class="about__heading">
                <h2 id="stats-title" class="about__title about__title--small">La maison en chiffres</h2>
                <span class="about__divider"></span>
            </div>

            <ul class="about__stats">
                <li class="about__stat">
                    <!-- calculé à partir de l'année de création : ne vieillit pas -->
                    <?php $yearsOfCraft = (int) date('Y') - 1987; ?>
                    <span class="about__stat-number" data-count="<?= $yearsOfCraft ?>"><?= $yearsOfCraft ?></span>
                    <span class="about__stat-label">ans de savoir-faire</span>
                </li>
                <li class="about__stat">
                    <span class="about__stat-number" data-count="3">3</span>
                    <span class="about__stat-label">générations de chocolatiers</span>
                </li>
                <li class="about__stat">
                    <span class="about__stat-number" data-count="120">120</span>
                    <span class="about__stat-label">créations au catalogue</span>
                </li>
                <li class="about__stat">
                    <span class="about__stat-number" data-count="100" data-suffix=" %">100 %</span>
                    <span class="about__stat-label">fait main à Liège</span>
                </li>
            </ul>
        </section>

        <!-- 5. FRISE CHRONOLOGIQUE : liste ordonnée, un trait doré relie les dates -->
        <section class="about__section" aria-labelledby="timeline-title">
            <div class="about__heading">
                <h2 id="timeline-title" class="about__title about__title--small">Notre parcours</h2>
                <span class="about__divider"></span>
            </div>

            <ol class="about__timeline">
                <li class="about__event">
                    <span class="about__event-year">1987</span>
                    <p>Rosalie Moreau ouvre sa première boutique dans le centre de Liège.</p>
                </li>
                <li class="about__event">
                    <span class="about__event-year">1995</span>
                    <p>Naissance de la praline « Rosalie », au praliné noisette et chocolat noir, devenue notre signature.</p>
                </li>
                <li class="about__event">
                    <span class="about__event-year">2008</span>
                    <p>Sa fille Claire reprend l'atelier et agrandit la boutique : la deuxième génération est aux fourneaux.</p>
                </li>
                <li class="about__event">
                    <span class="about__event-year">2019</span>
                    <p>Arrivée de la troisième génération et premiers achats de fèves en direct auprès des coopératives.</p>
                </li>
                <li class="about__event">
                    <span class="about__event-year">2026</span>
                    <p>Le livre de recettes de la famille s'ouvre à tous, en ligne.</p>
                </li>
            </ol>
        </section>

    </main>
    <?php require_once "./../view/publicView/inc/footer.php" ?>
    <script src="./js/scripts.js"></script>