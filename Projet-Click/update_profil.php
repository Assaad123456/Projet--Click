<?php
session_start();

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');

    // Chargement du fichier JSON des utilisateurs
    $fichier = _DIR_ . '/data/users.json';
    if (!file_exists($fichier)) {
        die("Fichier utilisateur introuvable.");
    }

    $utilisateurs = json_decode(file_get_contents($fichier), true);
    if (!$utilisateurs) {
        die("Erreur de lecture des utilisateurs.");
    }

    // Identifie l'utilisateur courant (exemple : par email ou login)
    $email = $_SESSION['user']['email'];
    $trouve = false;

    foreach ($utilisateurs as &$user) {
        if ($user['email'] === $email) {
            $user['nom'] = $nom;
            $user['adresse'] = $adresse;
            $_SESSION['user']['nom'] = $nom;
            $_SESSION['user']['adresse'] = $adresse;
            $trouve = true;
            break;
        }
    }

    if ($trouve) {
        file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));
        header("Location: profil.php?updated=1");
        exit();
    } else {
        echo "Utilisateur non trouvé.";
    }
} else {
    echo "Requête non autorisée.";
}