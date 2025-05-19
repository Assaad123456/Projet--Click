<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php if (isset($_SESSION['login'])): ?>
    <form action="logout.php" method="post" style="display:inline;">
        <button type="submit" style="padding: 8px 16px; background-color: #c0392b; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🔓 Logout
        </button>
    </form>

    <!-- Bouton panier ajouté -->
    <a href="panier.php" class="bouton-panier" style="padding: 8px 16px; background-color: #007bff; color: white; border-radius: 5px; text-decoration: none; margin-left: 10px; font-weight: bold;">
        🛒 Mon panier
    </a>
<?php endif; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Click-Journey</title>

    <!-- Lien vers le CSS -->
    <link id="theme-link" rel="stylesheet" href="style.css">

    <!-- Script pour le changement de thème -->
    <script src="js/theme.js" defer></script>
</head>
<body>
    <header class="main-header">
        <div class="header-left">
            <?php if (isset($_SESSION['login'])): ?>
                <a href="profil.php" class="profil-link">👤 Profil</a>
            <?php endif; ?>
        </div>

        <div class="header-center">
            <h1>Bienvenue sur <span class="highlight">Click-Journey</span></h1>
            <p class="subtitle">Explorez des destinations uniques à travers le monde</p>
        </div>

        <div class="header-right">
            <button onclick="switchTheme()" class="theme-button">🌓 Thème</button>
        </div>
    </header>
</body>

