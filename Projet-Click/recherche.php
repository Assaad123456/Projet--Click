<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Chargement des voyages
$fichier = "data/voyages.json";
$voyages = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $voyages = json_decode($contenu, true);
}

// Récupérer la recherche
$recherche = isset($_GET['q']) ? trim(strtolower($_GET['q'])) : '';
$resultats = [];

if ($recherche !== '') {
    foreach ($voyages as $voyage) {
        if (
            strpos(strtolower($voyage['titre']), $recherche) !== false ||
            strpos(strtolower($voyage['image']), $recherche) !== false
        ) {
            $resultats[] = $voyage;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche de voyages</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include("header.php"); ?>

<div class="search-container">
    <h2>🔎 Rechercher un voyage</h2>
    <form method="get" action="recherche.php">
        <input type="text" name="q" class="search-input" placeholder="Ex : Japon, plage, safari..." value="<?php echo htmlspecialchars($recherche); ?>">
        <button type="submit" class="search-btn">Rechercher</button>
    </form>
</div>

<div class="voyages">
    <?php if ($recherche !== ''): ?>
        <?php if (count($resultats) > 0): ?>
            <?php foreach ($resultats as $voyage): ?>
                <div class="card">
                    <img src="<?php echo htmlspecialchars($voyage['image']); ?>" alt="">
                    <h3><?php echo htmlspecialchars($voyage['titre']); ?></h3>
                    <p>Prix : <?php echo $voyage['prix']; ?> €</p>
                    <a href="voyage_detail.php?id=<?php echo $voyage['id']; ?>">Voir le détail</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">Aucun voyage ne correspond à votre recherche.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
