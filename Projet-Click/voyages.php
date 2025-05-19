<?php
// Chargement des voyages
$fichier = "data/voyages.json";
$voyages = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $voyages = json_decode($contenu, true);
}

// Pagination
$parPage = 6;
$total = count($voyages);
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalPages = ceil($total / $parPage);
$debut = ($page - 1) * $parPage;
$voyages_affiches = array_slice($voyages, $debut, $parPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos Voyages</title>
    <link id="theme-link" rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .voyages {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 300px;
            padding: 16px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
        }
        .card h3 {
            margin: 10px 0 6px;
        }
        .card p {
            margin: 5px 0;
        }
        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 14px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }
        .card a:hover {
            background-color: #0056b3;
        }
        .pagination {
            text-align: center;
            margin-top: 30px;
        }
        .pagination a {
            padding: 8px 12px;
            margin: 0 5px;
            background: #eee;
            text-decoration: none;
            border-radius: 5px;
        }
        .pagination a.active {
            background: #007bff;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<div class="header-voyages">
    <h1>🌍 Liste des voyages</h1>
    <p>Découvrez nos destinations disponibles autour du monde</p>
</div>
<body>



<div class="voyages">
    <?php foreach ($voyages_affiches as $voyage): ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($voyage['image']); ?>" alt="">
            <h3><?php echo htmlspecialchars($voyage['titre']); ?></h3>
            <p><strong>Dates :</strong> <?php echo $voyage['date_debut']; ?> → <?php echo $voyage['date_fin']; ?></p>
            <p><strong>Prix :</strong> <?php echo $voyage['prix']; ?> €</p>
            <p><strong>Étapes :</strong> <?php echo count($voyage['etapes']); ?></p>
            <a href="voyage_detail.php?id=<?php echo $voyage['id']; ?>">Voir en détail</a>
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>
</div>

</body>
</html>