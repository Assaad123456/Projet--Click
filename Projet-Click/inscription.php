<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $motdepasse = $_POST['motdepasse'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $naissance = $_POST['naissance'];
    $adresse = $_POST['adresse'];

    $fichier = 'data/utilisateurs.json';
    $utilisateurs = [];

    if (file_exists($fichier)) {
        $json = file_get_contents($fichier);
        $utilisateurs = json_decode($json, true);
    }

    // Vérifie si le login existe déjà
    foreach ($utilisateurs as $user) {
        if ($user['login'] === $login) {
            echo "<p style='color:red;text-align:center;'>Ce login existe déjà. Choisissez-en un autre.</p>";
            exit;
        }
    }

    // Création du nouvel utilisateur
    $nouvel_utilisateur = [
        "login" => $login,
        "motdepasse" => $motdepasse,
        "role" => "client",
        "nom" => $nom,
        "prenom" => $prenom,
        "naissance" => $naissance,
        "adresse" => $adresse,
        "date_inscription" => date("Y-m-d"),
        "derniere_connexion" => date("Y-m-d")
    ];

    // Ajout au tableau puis sauvegarde
    $utilisateurs[] = $nouvel_utilisateur;
    file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));

    // Connexion automatique et redirection
    $_SESSION['login'] = $nouvel_utilisateur;
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h2 {
            text-align: center;
            margin-top: 30px;
        }

        .my-form button {
            padding: 8px 12px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .my-form input {
            padding: 8px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
    <script>
        function validateForm() {
            let x = document.forms["myForm"]["login"].value;
            if (x.length < 3) {
                alert("Le login doit contenir au moins 3 caractères");
                return false;
            }
        }

        function changeFormat() {
            let myType = document.forms["myForm"]["motdepasse"].type;
            document.forms["myForm"]["motdepasse"].type = (myType === "password") ? "text" : "password";
        }
    </script>
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

<main>
    <form name="myForm" method="post" action="inscription.php" onsubmit="return validateForm()" style="max-width:400px;margin:auto;">
        <table class="my-form">
            <tr><td><h2>Créer un compte</h2></td></tr>
            <tr><td><label>Login</label></td></tr>
            <tr><td><input type="text" name="login" required></td></tr>

            <tr><td><label>Mot de passe</label></td></tr>
            <tr>
                <td>
                    <input type="password" name="motdepasse" required>
                    <button type="button" onclick="changeFormat()">👁</button>
                </td>
            </tr>

            <tr><td><label>Nom</label></td></tr>
            <tr><td><input type="text" name="nom" required></td></tr>

            <tr><td><label>Prénom</label></td></tr>
            <tr><td><input type="text" name="prenom" required></td></tr>

            <tr><td><label>Date de naissance</label></td></tr>
            <tr><td><input type="date" name="naissance" required></td></tr>

            <tr><td><label>Adresse</label></td></tr>
            <tr><td><input type="text" name="adresse" required></td></tr>

            <tr><td style="text-align:center; padding-top: 15px;"><button type="submit">S'inscrire</button></td></tr>
        </table>
    </form>
</main>

</body>
</html>
