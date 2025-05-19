<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement échoué</title>
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 80px; }
        .msg {
            background: #f8d7da; color: #721c24;
            padding: 20px; max-width: 500px;
            border-radius: 10px; margin: auto;
        }
        a, button {
            margin-top: 20px;
            display: inline-block;
            background: #007bff; color: white;
            padding: 10px 20px; text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="msg">
        <h1>❌ Paiement invalide</h1>
        <p>Une erreur est survenue lors de la vérification des informations bancaires.</p>
        <p>Veuillez vérifier vos données ou réessayer plus tard.</p>

        <a href="javascript:history.back()">Retour au paiement</a>
        <a href="index.php">Accueil</a>
    </div>

</body>
</html>