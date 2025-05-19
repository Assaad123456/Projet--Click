<?php
session_start();

if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    header("Location: connexion.php");
    exit();
}

if (isset($_POST['index']) && isset($_SESSION['panier'][$_POST['index']])) {
    unset($_SESSION['panier'][$_POST['index']]);
    $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexe le tableau
}

header("Location: panier.php");
exit();