<?php
/*
 * Modèle : accès aux données des recettes.
 */

// liste légère des recettes pour le menu déroulant (BE-10) : [['slug' => ..., 'title' => ...], ...]
function getNavRecipes(): array
{
    try {
        $connection = new PDO(DB_DSN, DB_LOGIN, DB_PWD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return $connection->query('SELECT slug, title FROM recipes ORDER BY title')->fetchAll();
    } catch (PDOException $e) {
        // base indisponible : le détail va dans le journal du serveur, jamais à l'écran (QC-05, SEC-10)
        error_log($e->getMessage());
        return [];
    }
}
