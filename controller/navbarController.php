<?php
/*
 * Contrôleur : prépare les données affichées par la vue inc/navbar.php.
 * Il est inclus par public/index.php, après session_start() et config.init.php.
 */

// recettes du menu déroulant (BE-10)
require_once __DIR__ . '/../model/recipeModel.php';
$navRecipes = getNavRecipes();

// utilisateur connecté (['username' => ..., 'role' => 'user' | 'admin']) ou null
$currentUser = $_SESSION['user'] ?? null;

// jeton CSRF créé une seule fois par session, envoyé avec la déconnexion (SEC-03)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];
