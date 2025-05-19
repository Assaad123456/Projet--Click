<?php
session_start();

if (!isset($_POST['voyage_id'], $_POST['prix_total'], $_POST['options_serialisees'])) {
    echo "Erreur : données incomplètes.";
    exit;
}

$commande = [
    'date' => date('Y-m-d H:i:s'),
    'utilisateur' => $_SESSION['login'] ?? 'invité',
    'voyage_id' => $_POST['voyage_id'],
    'prix_total' => floatval($_POST['prix_total']),
    'options' => unserialize(base64_decode($_POST['options_serialisees'])),
    'cb_hash' => substr(hash('sha256', implode('', $_POST)), 0, 12) // pseudonymisation CB
];

// Sauvegarde
$fichier = "data/commandes.json";
$commandes = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];
$commandes[] = $commande;
file_put_contents($fichier, json_encode($commandes, JSON_PRETTY_PRINT));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement validé</title>
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 100px; }
        .msg { background: #d4edda; color: #155724; padding: 20px; border-radius: 10px; max-width: 500px; margin: auto; }
    </style>
</head>
<body>
    <div class="msg">
        <h1>✅ Paiement validé</h1>
        <p>Merci pour votre réservation !</p>
        <a href="index.php">Retour à l'accueil</a>
    </div>
</body>
</html>