<?php
session_start();

if (!isset($_GET['voyage_id']) || !isset($_GET['readonly'])) {
    echo "Accès non autorisé.";
    exit;
}

$voyage_id = $_GET['voyage_id'];
$utilisateur = $_SESSION['login'] ?? 'invité';

$voyages = json_decode(file_get_contents("data/voyages.json"), true);
$commandes = json_decode(file_get_contents("data/commandes.json"), true);

$voyage = null;
foreach ($voyages as $v) {
    if ($v['id'] == $voyage_id) {
        $voyage = $v;
        break;
    }
}

$options = [];
foreach ($commandes as $cmd) {
    if ($cmd['voyage_id'] == $voyage_id && $cmd['utilisateur'] == $utilisateur) {
        $options = $cmd['options'];
        break;
    }
}

if (!$voyage || empty($options)) {
    echo "Voyage réservé non trouvé.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($voyage['titre']); ?> – Vue lecture seule</title>
    <style>
        body { font-family: sans-serif; background: #f2f2f2; padding: 20px; }
        h1, h2 { text-align: center; }
        .etape {
            background: white;
            max-width: 700px;
            margin: 20px auto;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        ul { margin: 0; padding-left: 20px; }
    </style>
</head>
<body>

<h1><?php echo htmlspecialchars($voyage['titre']); ?></h1>
<p style="text-align:center;">Du <?php echo $voyage['date_debut']; ?> au <?php echo $voyage['date_fin']; ?></p>

<?php foreach ($voyage['etapes'] as $i => $etape): ?>
    <div class="etape">
        <h2><?php echo htmlspecialchars($etape['titre']); ?></h2>
        <p><strong>Lieu :</strong> <?php echo htmlspecialchars($etape['lieu']); ?></p>

        <?php if (!empty($options[$i]['hebergement'])): ?>
            <p><strong>Hébergement réservé :</strong> <?php echo htmlspecialchars($options[$i]['hebergement']); ?></p>
        <?php endif; ?>

        <?php if (!empty($options[$i]['restauration'])): ?>
            <p><strong>Restauration réservée :</strong> <?php echo htmlspecialchars($options[$i]['restauration']); ?></p>
        <?php endif; ?>

        <?php if (!empty($options[$i]['activites'])): ?>
            <p><strong>Activités réservées :</strong></p>
            <ul>
                <?php foreach ($options[$i]['activites'] as $j => $a): ?>
                    <?php if (!empty($a['choisi'])): ?>
                        <li>
                            <?php echo htmlspecialchars($etape['activites'][$j]['titre']); ?> – 
                            <?php echo intval($a['nb']); ?> personne(s)
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</body>
</html>

