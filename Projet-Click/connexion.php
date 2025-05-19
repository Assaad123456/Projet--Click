<?php
session_start();

// Si déjà connecté, redirection vers index
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists('data/utilisateurs.json')) {
        $json = file_get_contents('data/utilisateurs.json');
        $utilisateurs = json_decode($json, true);

        foreach ($utilisateurs as $u) {
            if ($u['login'] === $_POST['login'] && $u['motdepasse'] === $_POST['motdepasse']) {
                $_SESSION['login'] = $u['login'];
                $_SESSION['utilisateur'] = $u;
                header("Location: index.php");
                exit;
            }
        }
    }
    $erreur = "❌ Login ou mot de passe incorrect.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="connexion-container">
    <h1>🔐 Connexion</h1>

    <?php if (!empty($erreur)) : ?>
        <p class="erreur"><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="login" placeholder="Votre login" required>
        <input type="password" name="motdepasse" placeholder="Votre mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</div>

</body>
</html>
