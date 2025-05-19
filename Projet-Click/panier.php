<?php
session_start();

if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    header("Location: connexion.php");
    exit();
}

$panier = $_SESSION['panier'] ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-image: url('images/fond-panier.jpg'); /* Remplace par le nom réel de ton image */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .panier-container {
            max-width: 600px;
            margin: 60px auto;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            color: #222;
            text-align: center;
        }

        .panier-container h2 {
            font-size: 28px;
            margin-bottom: 25px;
            color: #007bff;
        }

        .panier-item {
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(240, 240, 240, 0.85);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .panier-item p {
            margin: 8px 0;
            font-size: 17px;
        }

        .panier-item form {
            margin-top: 10px;
        }

        .panier-item button {
            padding: 10px 16px;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            margin-right: 10px;
            font-size: 15px;
            transition: background-color 0.2s ease;
        }

        .panier-item button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="panier-container">
    <h2>🛒 Mon Panier</h2>
    

    <?php if (empty($panier)): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <?php foreach ($panier as $index => $item): ?>
            <div class="panier-item">
                <p><strong><?= htmlspecialchars($item['voyage']['titre']) ?></strong></p>
                <p>Dates : <?= $item['voyage']['date_debut'] ?> → <?= $item['voyage']['date_fin'] ?></p>
                <p>Prix total : <?= number_format($item['prix_total'], 2) ?> €</p>

                <form method="post" action="supprimer_panier.php">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button type="submit">🗑️ Supprimer</button>
                </form>

                <form method="post" action="paiement.php">
                    <input type="hidden" name="voyage_id" value="<?= $item['voyage']['id'] ?>">
                    <input type="hidden" name="prix_total" value="<?= $item['prix_total'] ?>">
                    <input type="hidden" name="options_serialisees" value="<?= base64_encode(serialize($item['options'])) ?>">
                    <button type="submit">💳 Payer</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

</body>
</html>
