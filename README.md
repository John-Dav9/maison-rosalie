# 🍫 La Maison Rosalie — Guide complet du site

Bienvenue dans la documentation de **La Maison Rosalie**, un site de recettes au chocolat. Ce document est écrit pour être compréhensible même si vous ne connaissez pas la programmation. Il explique à quoi sert le site, comment ses différentes parties fonctionnent, comment installer le projet et comment sa base de données est organisée.

> **À quoi sert ce document ?** À permettre à une personne qui découvre le projet de comprendre le site, de retrouver les fichiers importants et de l’installer sur son ordinateur en suivant les étapes dans l’ordre.

---

## Sommaire

1. [Présentation du projet](#1-présentation-du-projet)
2. [Ce que les visiteurs peuvent faire](#2-ce-que-les-visiteurs-peuvent-faire)
3. [Comment le site fonctionne](#3-comment-le-site-fonctionne)
4. [Technologies utilisées, expliquées simplement](#4-technologies-utilisées-expliquées-simplement)
5. [Organisation des fichiers](#5-organisation-des-fichiers)
6. [Les pages du site](#6-les-pages-du-site)
7. [La base de données expliquée](#7-la-base-de-données-expliquée)
8. [Les relations entre les tables](#8-les-relations-entre-les-tables)
9. [Installer le projet sur son ordinateur](#9-installer-le-projet-sur-son-ordinateur)
10. [Configurer la connexion à la base de données](#10-configurer-la-connexion-à-la-base-de-données)
11. [Lancer et vérifier le site](#11-lancer-et-vérifier-le-site)
12. [Images et autres ressources](#12-images-et-autres-ressources)
13. [Tests](#13-tests)
14. [Sécurité et bonnes pratiques](#14-sécurité-et-bonnes-pratiques)
15. [Dépannage](#15-dépannage)
16. [Glossaire](#16-glossaire)

---

## 1. Présentation du projet

**La Maison Rosalie** est un site de recettes au chocolat qui représente la vitrine en ligne d’une chocolaterie belge fictive. Le projet transforme un livre de recettes en un site où les visiteurs peuvent découvrir des desserts et consulter leur préparation.

Les cinq recettes présentes dans les données fournies sont :

- Éclair au chocolat
- Fondant au chocolat
- Mousse au chocolat
- Parfait glacé au chocolat
- Royal au chocolat

Le site utilise une identité visuelle inspirée du chocolat artisanal : des tons bruns, caramel et rose poudré. Le slogan du projet est : **« La douceur faite chocolat »**.

### Objectif du projet

Le but est de proposer plus qu’une simple page contenant du texte. Les recettes et leurs informations sont conservées dans une base de données. Le site peut donc lire ces informations et les afficher aux visiteurs.

Le cahier des charges prévoit également des comptes utilisateurs, des notes, des commentaires, un formulaire de contact et un classement des recettes les mieux notées. Toutes ces fonctionnalités doivent être vérifiées dans la version du code utilisée : une fonctionnalité prévue dans le cahier des charges n’est pas automatiquement considérée comme terminée.

---

## 2. Ce que les visiteurs peuvent faire

### Visiteur non connecté

Une personne qui n’a pas de compte ou qui n’est pas connectée peut consulter les pages publiques et lire les recettes. Pour les actions réservées aux membres, le site peut lui demander de se connecter.

### Utilisateur connecté

Un utilisateur disposant d’un compte peut, selon les fonctionnalités activées dans la version du projet :

- se connecter et se déconnecter ;
- consulter les recettes ;
- noter une recette ;
- publier un commentaire.

Une note est une appréciation en étoiles. Le modèle de données prévoit une note par utilisateur et par recette : si la personne note de nouveau la même recette, sa note est censée remplacer la précédente plutôt que créer un deuxième vote.

### Administrateur

Le projet possède une partie privée et une vue d’administration. Un administrateur est un compte auquel un rôle particulier donne des droits supplémentaires. Les actions réellement disponibles doivent être vérifiées dans le code de la version livrée.

### Formulaire de contact

Le visiteur peut remplir un formulaire avec son nom, son adresse e-mail, un sujet éventuel et son message. Le projet possède une table destinée à conserver les messages reçus.

---

## 3. Comment le site fonctionne

Pour comprendre le fonctionnement du site, imaginez un restaurant :

1. **Le visiteur** demande une page en cliquant sur un lien.
2. **Le navigateur** (Chrome, Firefox, Edge, etc.) affiche la page.
3. **PHP**, qui fonctionne sur le serveur, prépare les informations nécessaires.
4. **La base de données** conserve les recettes, les ingrédients, les étapes et les autres informations.
5. PHP récupère les données nécessaires et prépare la réponse.
6. Le navigateur affiche le résultat au visiteur.

Les fichiers HTML, CSS et JavaScript gèrent ce que la personne voit et les interactions dans son navigateur. PHP gère le traitement côté serveur. La base de données conserve les informations pour qu’elles ne disparaissent pas lorsque la page est fermée.

---

## 4. Technologies utilisées, expliquées simplement

| Technologie | À quoi sert-elle dans le projet ? |
|---|---|
| **HTML** | Organise le contenu d’une page : titres, paragraphes, images, formulaires et liens. |
| **CSS** | Définit l’apparence du site : couleurs, tailles, espacements et mise en page. |
| **JavaScript** | Permet d’ajouter des interactions dans le navigateur. |
| **Bootstrap** | Fournit des outils de mise en page qui aident le site à s’adapter aux téléphones, tablettes et ordinateurs. |
| **PHP** | Exécute les traitements sur le serveur, par exemple récupérer une recette ou enregistrer un message. |
| **MariaDB / MySQL** | Conserve les données dans des tables reliées entre elles. Le fichier SQL fourni a été exporté depuis MariaDB. |
| **PDO** | Permet au code PHP de communiquer avec la base de données. |
| **Git** | Enregistre l’historique des changements apportés au projet. |
| **GitHub** | Héberge le dépôt Git pour permettre le partage et le travail en équipe. |

Le code fourni est organisé en classes et en gestionnaires de données PHP. Aucun framework PHP comme Laravel n’est nécessaire pour lire le projet actuel.

---

## 5. Organisation des fichiers

Voici les principaux dossiers et fichiers du projet, avec leur rôle expliqué en langage simple.

```text
La-Maison-Rosalie/
├── .gitignore
├── config.init.php
├── config.php
├── index.php
├── api/
├── controller/
│   ├── publicController.php
│   └── privateController.php
├── data/
│   └── data-base.sql
├── model/
│   ├── manager/
│   ├── mapping/
│   ├── AbstractMapping.php
│   ├── ContactText.php
│   ├── ManagerInterface.php
│   ├── StringTrait.php
│   └── UserInterface.php
├── public/
│   ├── index.php
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   ├── about.js
│   │   └── scripts.js
│   └── assets/
│       ├── images/
│       └── logo/
├── tests/
│   └── contact_security_test.php
└── view/
    ├── publicView/
    └── privateView/
```

### Les fichiers à la racine

- **`index.php`** : point d’entrée situé à la racine du projet. Dans cette version, il redirige vers l’entrée publique.
- **`config.init.php`** : modèle de configuration qui indique les paramètres à renseigner pour connecter le site à la base de données.
- **`config.php`** : configuration locale utilisée par le projet. Elle contient des paramètres propres à l’ordinateur sur lequel le site est lancé. Elle ne doit pas contenir d’informations sensibles dans un dépôt public.
- **`.gitignore`** : liste les fichiers que Git ne doit pas ajouter au dépôt. Il faut vérifier que les fichiers nécessaires à l’installation, notamment le script SQL de démonstration, sont bien partagés avec l’équipe.

### Le dossier `controller/` — les contrôleurs

Un contrôleur reçoit une demande, décide du traitement à effectuer et choisit la page à afficher.

- **`publicController.php`** : gère les demandes liées aux pages publiques.
- **`privateController.php`** : gère les demandes liées à la partie privée.

### Le dossier `model/` — les données et les traitements

Ce dossier contient le code qui représente les données et qui communique avec la base de données.

#### `model/manager/`

Les gestionnaires exécutent les opérations sur les données :

- **`RecetteManager.php`** : récupère les recettes et leurs informations.
- **`UserManager.php`** : gère les opérations liées aux utilisateurs.
- **`NoteManager.php`** : récupère et traite les notes.
- **`CommentManager.php`** : récupère et ajoute des commentaires.
- **`ContactManager.php`** : enregistre et récupère les messages de contact.
- **`ContactMailer.php`** : contient le traitement lié à l’envoi de messages par e-mail.

#### `model/mapping/`

Les fichiers de correspondance, parfois appelés *mapping*, transforment les lignes renvoyées par la base de données en objets utilisables par le code PHP. On y trouve notamment des fichiers pour les catégories, commentaires, contacts, ingrédients, notes, recettes, étapes et utilisateurs.

#### Autres fichiers du modèle

- **`AbstractMapping.php`** : classe de base partagée par des classes de correspondance.
- **`ContactText.php`** : contient des traitements liés au texte des formulaires de contact.
- **`ManagerInterface.php`** et **`UserInterface.php`** : définissent des règles communes que certaines classes doivent respecter.
- **`StringTrait.php`** : regroupe des méthodes réutilisables autour des chaînes de caractères.

### Le dossier `view/` — les pages affichées

- **`view/publicView/`** contient les pages visibles par les visiteurs.
- **`view/privateView/`** contient les pages privées et celles de l’administration.
- Le sous-dossier **`inc/`** contient des éléments réutilisés sur plusieurs pages, par exemple l’en-tête, le menu, le pied de page ou les fenêtres de connexion.

### Le dossier `public/` — ce que le navigateur peut charger

- **`public/index.php`** : point d’entrée public de l’application.
- **`public/css/style.css`** : règles qui définissent l’apparence du site.
- **`public/js/scripts.js`** : scripts JavaScript communs.
- **`public/js/about.js`** : scripts associés à la page « À propos ».
- **`public/assets/`** : images, logos et autres ressources visuelles.

### Le dossier `data/`

Le fichier **`data/data-base.sql`** contient une exportation SQL de la base de données : il décrit les tables et contient des données de départ. Il sert à reconstruire la base sur un nouvel ordinateur.

### Le dossier `tests/`

Le fichier **`contact_security_test.php`** contient des tests liés au traitement des données du formulaire de contact. Consultez la section [Tests](#13-tests) pour les lancer.

### Le dossier `api/`

Ce dossier existe dans le projet. Il ne contient actuellement qu’un fichier `.gitkeep`, utilisé pour que Git conserve un dossier vide. Cela ne signifie pas qu’une API complète est déjà disponible dans ce dossier.

---

## 6. Les pages du site

### Accueil

La page d’accueil présente la Maison Rosalie et son univers. Le cahier des charges prévoit aussi de mettre en avant les trois recettes les mieux notées. Le contenu exact visible dépend de la version du site et des données enregistrées.

### Recettes

Cette page permet de retrouver les recettes disponibles. Les titres et les informations des recettes sont stockés dans la base de données. Chaque recette possède un identifiant lisible appelé *slug*, par exemple `fondant-au-chocolat`, qui peut servir à la retrouver dans une adresse.

### Détail d’une recette

Une page de recette peut présenter :

- son titre et sa description ;
- sa photo principale ;
- le temps de préparation et le temps de cuisson ;
- sa difficulté et le nombre de portions ;
- la liste des ingrédients avec leurs quantités ;
- les étapes de préparation et leurs photos ;
- les notes et les commentaires, lorsque ces fonctionnalités sont disponibles.

### À propos

Cette page présente l’histoire et l’identité de la Maison Rosalie.

### Contact

Cette page présente un formulaire destiné à envoyer un message à la chocolaterie. Les messages valides peuvent être conservés dans la table `contact`.

### Fenêtres de connexion et mentions légales

Les fichiers `authModal.php` et `legalModal.php`, dans `view/publicView/inc/`, correspondent aux fenêtres intégrées aux pages pour l’authentification et les informations légales.

### Page d’erreur 404

`404View.php` est prévue pour afficher une page adaptée lorsqu’une page demandée n’existe pas, plutôt qu’un écran vide.

### Partie privée

Le dossier `view/privateView/` contient notamment `admin.php` et le menu privé. L’accès à une page privée doit toujours être contrôlé côté serveur : cacher un lien dans le menu ne suffit pas à protéger une page.

---

## 7. La base de données expliquée

### Qu’est-ce qu’une base de données ?

Une base de données est un endroit organisé où le site conserve ses informations. Elle est composée de **tables**, comparables à des feuilles de calcul. Chaque table contient des lignes (les enregistrements) et des colonnes (les informations de chaque enregistrement).

Par exemple, la table `recipe` conserve les recettes. Une ligne correspond à une recette, et ses colonnes peuvent contenir son titre, sa description ou son temps de préparation.

Le projet utilise une base nommée **`chocolat`** dans le fichier SQL fourni.

### Quelques mots importants

- **ID** : numéro qui identifie un enregistrement de manière unique.
- **Clé primaire** : colonne, souvent appelée `Id`, qui identifie chaque ligne d’une table.
- **Clé étrangère** : colonne qui fait référence à une ligne d’une autre table et permet de relier les données.
- **Relation** : lien entre deux tables.
- **Valeur unique** : valeur qui ne peut pas être répétée dans la colonne concernée.
- **`NULL`** : indique qu’aucune valeur n’est renseignée pour ce champ.
- **Slug** : version simplifiée d’un titre destinée à être utilisée dans une adresse web. Exemple : « Fondant au chocolat » devient `fondant-au-chocolat`.

### 7.1 Table `Users` — les comptes

Cette table conserve les informations des personnes qui disposent d’un compte sur le site. Dans le modèle du projet, les recettes, les notes et les commentaires peuvent être associés à un utilisateur.

Elle sert notamment à distinguer les personnes connectées et à relier une action à son auteur. Les mots de passe ne doivent jamais être enregistrés en clair : la valeur stockée doit être un hachage non réversible adapté aux mots de passe.

### 7.2 Table `category` — les catégories

Elle conserve les catégories dans lesquelles les recettes peuvent être classées.

| Champ | Rôle |
|---|---|
| `Id` | Numéro unique de la catégorie. |
| `Title` | Nom affiché de la catégorie. |
| `Description` | Présentation facultative de la catégorie. |
| `slug` | Nom simplifié utilisé pour l’identifier dans une adresse ou le code. |

Les catégories présentes dans l’export fourni sont **Gâteaux**, **Mousses** et **Glacé**.

### 7.3 Table `recipe` — les recettes

C’est la table principale du livre de recettes.

| Champ | Rôle |
|---|---|
| `Id` | Numéro unique de la recette. |
| `Title` | Titre de la recette. |
| `Description` | Texte qui présente le dessert. |
| `Image` | Chemin vers la photo principale. |
| `duration_prep` | Durée de préparation enregistrée. |
| `duration_cook` | Durée de cuisson enregistrée. |
| `difficulty` | Niveau de difficulté. |
| `Created_at` | Date et heure de création de l’enregistrement. |
| `Users_Id` | Identifiant de l’utilisateur associé à la recette. |
| `portions` | Nombre de portions prévu. |
| `slug` | Identifiant lisible et unique de la recette. |

Le temps total peut être calculé à partir du temps de préparation et du temps de cuisson. Les durées sont actuellement enregistrées sous forme de texte, par exemple `10 min` ou `1h`.

### 7.4 Table `category_has_recipe` — le lien entre recettes et catégories

Cette table relie les recettes à leurs catégories.

| Champ | Rôle |
|---|---|
| `Category_Id` | Identifiant de la catégorie. |
| `Recipe_Id` | Identifiant de la recette. |

Pourquoi une table supplémentaire ? Parce qu’une catégorie peut contenir plusieurs recettes et qu’une recette peut, si le projet le permet, appartenir à plusieurs catégories. Cette table évite de répéter les informations de catégorie dans chaque recette.

### 7.5 Table `ingredient` — les ingrédients

Cette table conserve la liste des ingrédients disponibles.

| Champ | Rôle |
|---|---|
| `Id` | Numéro unique de l’ingrédient. |
| `Name` | Nom de l’ingrédient. |
| `image` | Chemin facultatif vers sa photo. |

Un ingrédient est enregistré une seule fois dans cette table, même s’il est utilisé dans plusieurs recettes. Par exemple, le beurre peut servir dans plusieurs desserts sans qu’il soit nécessaire de créer une nouvelle entrée « Beurre » pour chaque recette.

### 7.6 Table `ingredient_has_recipe` — les quantités d’ingrédients par recette

Cette table relie les ingrédients aux recettes et précise la quantité nécessaire pour chacune.

| Champ | Rôle |
|---|---|
| `id` | Numéro unique de la ligne. |
| `Ingredient_Id` | Identifiant de l’ingrédient utilisé. |
| `Recipe_Id` | Identifiant de la recette concernée. |
| `quantite` | Quantité à utiliser. |
| `unit` | Unité de mesure, par exemple g, cl ou cuillère. |

**Exemple :** la table `ingredient` contient une seule entrée pour « Beurre ». La table `ingredient_has_recipe` indique ensuite quelle quantité de beurre est nécessaire pour l’éclair, le fondant ou une autre recette.

Les champs `quantite` et `unit` peuvent être laissés à `NULL` dans le schéma fourni lorsque la quantité ou l’unité n’est pas précisée. Il faut cependant vérifier que l’affichage du site gère correctement ces cas.

### 7.7 Table `steps` — les étapes de préparation

Cette table conserve les différentes étapes d’une recette.

| Champ | Rôle |
|---|---|
| `Id` | Numéro unique de l’étape. |
| `Recette_id` / `Recipe_Id` | Référence à la recette concernée (le schéma contient les deux champs). |
| `nb_step` | Numéro qui détermine l’ordre de l’étape. |
| `Title` | Titre court de l’étape. |
| `Description` | Instructions à suivre. |
| `image` | Chemin vers la photo de l’étape. |

Le numéro d’étape permet de présenter les instructions dans le bon ordre. Les photos sont des fichiers enregistrés dans le dossier public du site ; la base conserve leur chemin, pas le fichier image lui-même.

> Le schéma SQL fourni contient à la fois `Recette_id` et `Recipe_Id` dans cette table. Il faut vérifier dans le code et dans la base active lequel est utilisé et si les deux colonnes sont réellement nécessaires avant de modifier le schéma.

### 7.8 Table `note` — les notes en étoiles

Cette table conserve les notes déposées par les utilisateurs.

| Champ | Rôle |
|---|---|
| `Id` | Numéro unique de la note. |
| `Note` | Valeur de la note. |
| `Created_at` | Date et heure de la note. |
| `Users_Id` | Utilisateur qui a donné la note. |
| `Recipe_Id` | Recette évaluée. |

La base prévoit une contrainte d’unicité sur le couple `Users_Id` et `Recipe_Id`. Cela signifie qu’un même utilisateur ne peut pas avoir deux lignes de note pour une même recette. Le cahier des charges prévoit des notes entières de 1 à 5 et le calcul de la moyenne à partir des notes enregistrées.

### 7.9 Table `comments` — les commentaires

Cette table conserve les commentaires associés aux recettes.

| Champ | Rôle |
|---|---|
| `Id` | Numéro unique du commentaire. |
| `Author` | Nom affiché de l’auteur. |
| `Subject` | Sujet facultatif. |
| `Message` | Texte du commentaire. |
| `Created_at` | Date et heure de publication. |
| `Recipe_Id` | Recette concernée. |
| `Users_Id` | Compte auquel le commentaire est associé. |
| `Status` | État du commentaire, par défaut `published` dans le schéma fourni. |

Le lien vers `Users_Id` permet d’associer le commentaire à un compte. Le serveur doit vérifier les droits avant de permettre la suppression d’un commentaire. Le contenu des commentaires doit être affiché comme du texte, jamais interprété comme du code.

### 7.10 Table `contact` — les messages du formulaire

Cette table conserve les messages envoyés depuis le formulaire de contact.

| Champ | Rôle |
|---|---|
| `Id` | Numéro unique du message. |
| `Name` | Nom de la personne. |
| `Email` | Adresse e-mail de réponse. |
| `Subject` | Sujet facultatif. |
| `Message` | Contenu du message. |
| `Created_at` | Date et heure de réception. |

Cette table est distincte des commentaires : un message de contact est adressé à la chocolaterie et ne correspond pas à un avis public sur une recette.

### Résumé des tables

| Table | À quoi sert-elle ? |
|---|---|
| `Users` | Comptes des utilisateurs et rôles. |
| `category` | Liste des catégories. |
| `recipe` | Informations principales des recettes. |
| `category_has_recipe` | Association entre recettes et catégories. |
| `ingredient` | Liste des ingrédients. |
| `ingredient_has_recipe` | Ingrédients, quantités et unités propres à chaque recette. |
| `steps` | Instructions de préparation et photos des étapes. |
| `note` | Notes des utilisateurs. |
| `comments` | Commentaires des utilisateurs. |
| `contact` | Messages envoyés par le formulaire de contact. |

---

## 8. Les relations entre les tables

La base de données évite de recopier les mêmes informations partout. Les tables sont reliées par des identifiants.

- **Une recette possède plusieurs étapes.** Chaque étape indique à quelle recette elle appartient.
- **Une recette utilise plusieurs ingrédients.** La table `ingredient_has_recipe` précise la quantité de chaque ingrédient.
- **Une recette peut être associée à une ou plusieurs catégories.** La table `category_has_recipe` assure le lien.
- **Un utilisateur peut publier plusieurs notes et commentaires.** Chaque note et commentaire conserve l’identifiant de son auteur.
- **Une note concerne une recette.** Le lien vers `Recipe_Id` indique quelle recette est évaluée.
- **Un message de contact est indépendant des recettes.** Il est conservé dans la table `contact`.

### Exemple concret

Pour afficher le fondant au chocolat :

1. Le site retrouve la recette dans `recipe`.
2. Il retrouve ses catégories grâce à `category_has_recipe` et `category`.
3. Il retrouve ses ingrédients grâce à `ingredient_has_recipe` et `ingredient`.
4. Il retrouve ses instructions dans `steps`.
5. Il peut calculer sa note moyenne à partir des lignes correspondantes dans `note`.
6. Il peut afficher les commentaires correspondants dans `comments`.

Cette organisation facilite la mise à jour des recettes. Modifier une quantité dans la table de liaison permet de changer cette quantité sans devoir recréer l’ingrédient.

---

## 9. Installer le projet sur son ordinateur

Cette procédure s’adresse à une personne qui découvre le projet. Il faut installer un environnement capable de faire fonctionner PHP et MariaDB/MySQL.

### Prérequis

Il faut disposer de :

- **PHP**, pour exécuter le code du site ;
- **MariaDB ou MySQL**, pour conserver les données ;
- **PDO MySQL**, l’extension PHP qui permet à PHP de communiquer avec la base ;
- un navigateur web ;
- un outil comme **phpMyAdmin** pour importer le fichier SQL, ou l’équivalent en ligne de commande.

Un environnement local tel que WAMP ou XAMPP peut fournir plusieurs de ces outils.

### Étape 1 — Décompresser le projet

Si le projet a été téléchargé au format ZIP :

1. Faites un clic droit sur le fichier ZIP.
2. Choisissez l’option d’extraction.
3. Ouvrez le dossier extrait `La-Maison-Rosalie`.

Si le projet est récupéré depuis GitHub, vous pouvez le cloner depuis un terminal :

```bash
git clone <URL_DU_DEPOT>
cd La-Maison-Rosalie
```

Remplacez `<URL_DU_DEPOT>` par l’adresse réelle du dépôt Git. Les chevrons ne doivent pas être saisis tels quels dans l’adresse.

### Étape 2 — Démarrer MariaDB/MySQL

Ouvrez votre environnement local et démarrez le service de base de données. Si vous utilisez WAMP ou XAMPP, vérifiez que le service MySQL/MariaDB est bien démarré.

Le port doit correspondre à celui utilisé par votre installation. Le fichier SQL fourni a été exporté depuis un serveur configuré sur le port `3307`, mais votre installation peut utiliser un autre port.

### Étape 3 — Créer la base `chocolat`

Dans phpMyAdmin :

1. Ouvrez phpMyAdmin depuis votre environnement local.
2. Cliquez sur **Nouvelle base de données**.
3. Saisissez `chocolat` comme nom.
4. Choisissez un encodage compatible avec `utf8mb4` si l’option est proposée.
5. Créez la base.

L’encodage `utf8mb4` permet notamment de conserver correctement les accents et de nombreux caractères spéciaux.

### Étape 4 — Importer le fichier SQL

Le fichier de départ est :

```text
data/data-base.sql
```

Dans phpMyAdmin :

1. Sélectionnez la base `chocolat`.
2. Ouvrez l’onglet **Importer**.
3. Sélectionnez `data/data-base.sql` sur votre ordinateur.
4. Lancez l’importation.
5. Vérifiez que les tables apparaissent dans la base.

**Attention :** le script SQL contient des instructions `DROP TABLE IF EXISTS`. Cela signifie que les tables portant ces noms peuvent être supprimées puis recréées lors de l’importation. Ne lancez pas cet import sur une base contenant des données à conserver sans en faire une sauvegarde.

Le fichier SQL est une exportation de la base et contient les tables ainsi que des données de recettes. Si l’importation échoue, consultez le message d’erreur : la version de MariaDB/MySQL et les paramètres de collation peuvent notamment avoir une incidence.

### Étape 5 — Configurer le site

Suivez les instructions de la section suivante pour adapter `config.php` à votre ordinateur.

### Étape 6 — Démarrer le site

Après configuration, suivez la section [Lancer et vérifier le site](#11-lancer-et-vérifier-le-site).

---

## 10. Configurer la connexion à la base de données

Le site doit connaître quatre informations principales pour se connecter à la base : l’adresse du serveur, le port, le nom de la base et les identifiants de connexion.

Le fichier `config.init.php` sert de modèle. Le projet fourni contient également un `config.php` local ; vérifiez son contenu avant de le remplacer, car il peut contenir les paramètres de votre environnement.

Exemple de paramètres locaux :

```php
const DB_TYPE = "mysql";
const DB_HOST = "localhost";
const DB_PORT = 3307;
const DB_NAME = "chocolat";
const DB_CHARSET = "utf8mb4";
const DB_LOGIN = "votre_utilisateur";
const DB_PWD = "votre_mot_de_passe";
```

Adaptez ces valeurs à votre installation :

- **`DB_TYPE`** : type de base de données. Le projet utilise ici MySQL/MariaDB.
- **`DB_HOST`** : adresse du serveur de base de données. Sur le même ordinateur, `localhost` est courant.
- **`DB_PORT`** : port sur lequel le serveur écoute. Vérifiez-le dans WAMP/XAMPP ou dans la configuration de MariaDB.
- **`DB_NAME`** : nom de la base créée, ici `chocolat`.
- **`DB_CHARSET`** : encodage utilisé pour communiquer avec la base.
- **`DB_LOGIN`** et **`DB_PWD`** : nom et mot de passe du compte de base de données.

N’utilisez pas automatiquement le port `3307` si votre serveur fonctionne sur `3306`. Le port configuré dans PHP doit être le même que celui du serveur.

**Sécurité :** ne publiez pas de vrais mots de passe dans GitHub. Un compte dédié avec des droits limités est préférable à un compte administrateur de base de données. Si le fichier `config.php` contient des identifiants personnels, ne le partagez pas.

---

## 11. Lancer et vérifier le site

### Méthode avec le serveur PHP intégré

Ouvrez un terminal dans le dossier du projet, puis lancez :

```bash
php -S localhost:8000 -t public
```

L’option `-t public` indique que le dossier `public` doit être utilisé comme racine accessible par le navigateur.

Ouvrez ensuite :

[http://localhost:8000](http://localhost:8000)

Pour arrêter le serveur, retournez dans le terminal et utilisez `Ctrl + C`.

### Méthode avec WAMP ou XAMPP

Vous pouvez aussi placer le dossier du projet dans le répertoire web de votre environnement local, puis ouvrir l’adresse locale correspondante dans le navigateur. Le chemin exact dépend de votre installation.

### Vérifications après démarrage

- La page d’accueil s’ouvre sans erreur.
- Les pages publiques sont accessibles.
- Les recettes et leurs images s’affichent.
- Les catégories et les ingrédients correspondent aux données de la base.
- Le formulaire de contact réagit correctement.
- Les fonctions de connexion, de note et de commentaire disponibles dans cette version ont été testées.
- Les pages restent lisibles sur un petit écran.

---

## 12. Images et autres ressources

Les images sont enregistrées sous forme de fichiers dans `public/assets/`. La base de données contient uniquement les chemins qui permettent de les retrouver.

### Dossiers utiles

- `public/assets/images/recettes/` : photos principales des recettes.
- `public/assets/images/etapes/` : photos des étapes.
- `public/assets/images/ingredients/` : photos des ingrédients.
- `public/assets/images/` : autres photos du site.
- `public/assets/logo/` : fichiers du logo.

### Exemple

Si la base contient le chemin :

```text
assets/images/recettes/fondant-au-chocolat.webp
```

le navigateur doit pouvoir retrouver le fichier correspondant dans le dossier public. Si une image ne s’affiche pas, vérifiez le nom du fichier, les majuscules/minuscules et le chemin enregistré dans la base.

Le format **WebP** est un format d’image adapté au web qui permet souvent d’obtenir des fichiers moins volumineux.

---

## 13. Tests

Le projet contient un fichier de test :

```text
tests/contact_security_test.php
```

Il porte sur des traitements liés au formulaire de contact. Les tests permettent de vérifier automatiquement certains comportements et de repérer des erreurs après une modification du code.

Depuis la racine du projet, vous pouvez essayer :

```bash
php tests/contact_security_test.php
```

Cette commande suppose que PHP est installé et accessible depuis le terminal. Si la commande `php` n’est pas reconnue, ouvrez le terminal fourni par votre environnement PHP ou ajoutez PHP au chemin système.

### Tests manuels recommandés

1. Ouvrir chaque page publique.
2. Ouvrir chacune des cinq recettes.
3. Vérifier les ingrédients, les quantités, les durées et les images.
4. Envoyer un formulaire de contact valide puis un formulaire invalide.
5. Tester la connexion avec des identifiants corrects et incorrects.
6. Vérifier qu’un visiteur non connecté ne peut pas effectuer une action réservée aux membres.
7. Vérifier les notes et commentaires si ces fonctions sont actives.
8. Tester l’affichage sur ordinateur et téléphone.
9. Vérifier le comportement du site lorsque la base de données n’est pas disponible.

Les résultats doivent être consignés dans un plan de tests : cas testé, résultat attendu et résultat réellement obtenu.

---

## 14. Sécurité et bonnes pratiques

Même un petit site doit protéger ses données et ses utilisateurs.

- **Mots de passe :** ne jamais les conserver en texte lisible. Utiliser les fonctions de hachage prévues pour les mots de passe.
- **Requêtes SQL :** utiliser des requêtes préparées pour éviter qu’une saisie soit interprétée comme une commande de base de données.
- **Validation :** vérifier les informations reçues sur le serveur, même si le navigateur vérifie déjà les champs.
- **Commentaires :** afficher le contenu comme du texte et non comme du code HTML ou JavaScript.
- **Droits :** vérifier côté serveur qu’une personne est autorisée à effectuer une action avant de l’exécuter.
- **Identifiants :** ne pas publier les mots de passe ou les secrets de configuration.
- **Messages d’erreur :** afficher un message compréhensible au visiteur et conserver les détails techniques dans les journaux du serveur.
- **Sauvegardes :** faire une copie de la base avant une modification importante ou un import qui peut supprimer des tables.

Cette liste présente les bonnes pratiques à respecter ; elle ne constitue pas une certification de sécurité de la version actuelle du site.

---

## 15. Dépannage

### Le site affiche une erreur de connexion à la base

Vérifiez que MariaDB/MySQL est démarré, que le nom de la base est `chocolat`, que le port est correct et que le compte de connexion dispose des droits nécessaires.

### Les recettes ne s’affichent pas

Vérifiez que le fichier SQL a bien été importé et que la table `recipe` contient des recettes. Vérifiez aussi les messages d’erreur PHP et les paramètres de connexion.

### Les images sont cassées

Vérifiez que les fichiers existent dans `public/assets/` et que les chemins enregistrés dans les colonnes `Image` ou `image` correspondent aux fichiers réels.

### Le terminal ne reconnaît pas `php`

PHP n’est peut-être pas installé ou n’est pas accessible depuis ce terminal. Essayez le terminal fourni avec votre environnement local ou configurez le chemin vers l’exécutable PHP.

### L’import SQL échoue

Lisez le message d’erreur complet. Vérifiez que vous avez sélectionné la bonne base, que MariaDB/MySQL est démarré et que la version utilisée accepte les instructions et l’encodage du fichier exporté.

### Le site fonctionne chez une personne, mais pas chez une autre

Comparez les versions de PHP et de MariaDB/MySQL, le port, les paramètres de `config.php`, l’état de la base et les fichiers présents. Deux ordinateurs peuvent avoir des configurations différentes.

---

## 16. Glossaire

| Terme | Explication simple |
|---|---|
| **Backend** | Partie qui travaille sur le serveur et traite les demandes. |
| **Frontend** | Partie du site que le visiteur voit et utilise dans son navigateur. |
| **Serveur local** | Environnement qui fait fonctionner le site sur son propre ordinateur, sans le publier sur Internet. |
| **Base de données** | Espace organisé qui conserve les informations du site. |
| **Table** | Ensemble de données organisé en lignes et en colonnes. |
| **Enregistrement** | Une ligne d’une table, par exemple une recette. |
| **Champ / colonne** | Une information d’un enregistrement, par exemple le titre d’une recette. |
| **ID** | Numéro permettant d’identifier un enregistrement. |
| **Clé étrangère** | Champ qui relie une ligne à une autre table. |
| **Import SQL** | Opération qui exécute un fichier contenant les instructions pour créer et remplir la base de données. |
| **Responsive** | Capacité du site à s’adapter à différentes tailles d’écran. |
| **Session** | Mécanisme permettant au site de reconnaître un utilisateur pendant sa navigation. |
| **Dépôt Git** | Dossier du projet accompagné de l’historique de ses changements. |
| **Commit** | Enregistrement d’un ensemble de modifications dans Git. |
| **Slug** | Identifiant lisible utilisé pour retrouver une page, comme `mousse-au-chocolat`. |
| **Hachage de mot de passe** | Transformation conçue pour stocker une représentation protégée du mot de passe plutôt que le mot de passe lui-même. |

---

## Pour aller plus loin

Le cahier des charges du projet prévoit également une documentation des échanges entre le navigateur et le serveur, un plan de tests, les comptes de démonstration et un rapport technique. Ces documents doivent être maintenus à jour avec le comportement réel du site.

**La Maison Rosalie — La douceur faite chocolat.**