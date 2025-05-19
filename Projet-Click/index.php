<?php
session_start();
include("header.php");

// Charger les voyages
$fichier = "data/voyages.json";
$voyages = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $voyages = json_decode($contenu, true);
    shuffle($voyages);
    $selection = array_slice($voyages, 0, 3);
} else {
    $selection = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil – Click-Journey</title>
    <link id="theme-link" rel="stylesheet" href="style.css">
    <script src="js/theme.js" defer></script>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background-image: url("images/road-5990128_1280.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        main {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 12px;
            margin: 20px auto;
            max-width: 1000px;
        }

        nav {
            text-align: center;
            margin: 20px;
        }

        nav a {
            margin: 0 10px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }

        .search-bar {
            text-align: center;
            margin-top: 20px;
        }

        .search-bar input {
            padding: 8px;
            width: 250px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .search-bar button {
            padding: 8px 12px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
        }

        .voyages {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px;
        }

        .card {
            background: white;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 16px;
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }

        .card h3 { margin: 10px 0; }

        .card a {
            display: inline-block;
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #eee;
            margin-top: 40px;
        }
    </style>
</head>
<body>

<main>

    <nav>
        <a href="voyages.php">Tous les voyages</a>
        <a href="recherche.php">Recherche</a>
        <a href="connexion.php">Connexion</a>
        <a href="inscription.php">Inscription</a>
    </nav>

    <div class="search-bar">
        <form action="recherche.php" method="get">
            <input type="text" name="q" placeholder="Ex : Japon, Safari, nature..." required>
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <h2>
        <?php
        echo isset($_SESSION['login']) ? 'Bienvenue ' . htmlspecialchars($_SESSION['login']) : 'Bienvenue invité';
        ?>
    </h2>

    <center><strong>Suggestions de voyages</strong></center>

    <div class="voyages">
        <?php foreach ($selection as $voyage): ?>
            <div class="card">
                <img src="<?php echo htmlspecialchars($voyage['image']); ?>" alt="">
                <h3><?php echo htmlspecialchars($voyage['titre']); ?></h3>
                <p><strong>Prix :</strong> <?php echo $voyage['prix']; ?> €</p>
                <p><strong>Dates :</strong> <?php echo $voyage['date_debut']; ?> → <?php echo $voyage['date_fin']; ?></p>
                <a href="voyage_detail.php?id=<?php echo $voyage['id']; ?>">Voir le détail</a>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<footer>
    <p>&copy; 2025 Click-Journey – Projet BTS</p>
</footer>

</body>
</html>
