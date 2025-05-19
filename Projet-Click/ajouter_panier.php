<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_POST['voyage_id']) || !isset($_POST['prix_total']) || !isset($_POST['options_serialisees'])) {
    header("Location: voyages.php");
    exit();
}

$voyages = json_decode(file_get_contents("data/voyages.json"), true);
$voyage_id = $_POST['voyage_id'];
$prix_total = floatval($_POST['prix_total']);
$options = unserialize(base64_decode($_POST['options_serialisees']));

$voyage = null;
foreach ($voyages as $v) {
    if ($v['id'] == $voyage_id) {
        $voyage = $v;
        break;
    }
}

if (!$voyage) {
    echo "Voyage introuvable.";
    exit();
}

$_SESSION['panier'][] = [
    'voyage' => $voyage,
    'options' => $options,
    'prix_total' => $prix_total
];

// ✅ Redirige vers panier après ajout
header("Location: panier.php");
exit();