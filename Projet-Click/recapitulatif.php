<?php
session_start();

if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_POST['voyage_id']) || (!isset($_POST['options']) && !isset($_POST['options_serialisees']))) {
    echo "Accès non autorisé.";
    exit;
}

$voyage_id = $_POST['voyage_id'];
$_SESSION['voyage_id'] = $voyage_id; // ✅ Pour retour_paiement.php

if (isset($_POST['options'])) {
    $options = $_POST['options'];
} elseif (isset($_POST['options_serialisees'])) {
    $options = unserialize(base64_decode($_POST['options_serialisees']));
}

$voyages = json_decode(file_get_contents("data/voyages.json"), true);
$voyage = null;

foreach ($voyages as $v) {
    if ($v['id'] == $voyage_id) {
        $voyage = $v;
        break;
    }
}

if (!$voyage) {
    echo "Voyage introuvable.";
    exit;
}

function calculerNbJours($date_debut, $date_fin) {
    $d1 = new DateTime($date_debut);
    $d2 = new DateTime($date_fin);
    return $d1->diff($d2)->days;
}

$prix_total = floatval($voyage['prix']);
$nb_total_personnes = 0;
$duree = calculerNbJours($voyage['date_debut'], $voyage['date_fin']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Récapitulatif</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        h1, h2 { text-align: center; }
        .bloc {
            background: white; padding: 20px;
            max-width: 700px; margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .flex { display: flex; justify-content: space-between; }
        .btns { text-align: center; margin-top: 20px; }
        .btns form { display: inline-block; margin: 0 10px; }
        button {
            padding: 10px 20px; background: #007bff;
            color: white; border: none; border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h1>Récapitulatif de votre voyage personnalisé</h1>
<div class="bloc">
    <p><strong>Destination :</strong> <?php echo htmlspecialchars($voyage['titre']); ?></p>
    <p><strong>Dates :</strong> <?php echo $voyage['date_debut']; ?> → <?php echo $voyage['date_fin']; ?> (<?php echo $duree; ?> jours)</p>
    <p><strong>Prix de base :</strong> <?php echo number_format($voyage['prix'], 2); ?> €</p>
</div>

<?php foreach ($voyage['etapes'] as $i => $etape): ?>
    <div class="bloc">
        <h2>Étape : <?php echo htmlspecialchars($etape['titre']); ?></h2>
        <p><strong>Lieu :</strong> <?php echo htmlspecialchars($etape['lieu']); ?></p>

        <?php if (!empty($options[$i]['hebergement'])): ?>
            <p><strong>Hébergement choisi :</strong> <?php echo $options[$i]['hebergement']; ?></p>
            <?php
                foreach ($etape['hebergements'] as $h) {
                    if ($h['titre'] == $options[$i]['hebergement']) {
                        $prix_total += $h['prix'];
                    }
                }
            ?>
        <?php endif; ?>

        <?php if (!empty($options[$i]['restauration'])): ?>
            <p><strong>Restauration choisie :</strong> <?php echo $options[$i]['restauration']; ?></p>
            <?php
                foreach ($etape['restauration'] ?? [] as $r) {
                    if ($r['titre'] == $options[$i]['restauration']) {
                        $prix_total += $r['prix'] * $duree;
                    }
                }
            ?>
        <?php endif; ?>

        <?php if (!empty($options[$i]['activites'])): ?>
            <p><strong>Activités sélectionnées :</strong></p>
            <ul>
                <?php foreach ($options[$i]['activites'] as $j => $a): ?>
                    <?php if (!empty($a['choisi'])): ?>
                        <li>
                            <?php echo $etape['activites'][$j]['titre']; ?>
                            – <?php echo intval($a['nb']); ?> personne(s)
                            (<?php echo $etape['activites'][$j]['prix']; ?> € x <?php echo intval($a['nb']); ?>)
                        </li>
                        <?php
                            $nb_total_personnes += intval($a['nb']);
                            $prix_total += $etape['activites'][$j]['prix'] * intval($a['nb']);
                        ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<div class="bloc">
    <h2 class="flex">
        <span>Total personnes : <?php echo $nb_total_personnes; ?></span>
        <span>Prix total : <?php echo number_format($prix_total, 2); ?> €</span>
    </h2>
</div>

<div class="btns">
    <!-- Modifier -->
    <form method="get" action="voyage_detail.php">
        <input type="hidden" name="id" value="<?php echo $voyage_id; ?>">
        <button type="submit">Modifier le voyage</button>
    </form>

    <!-- Ajouter au panier -->
    <form method="post" action="ajouter_panier.php">
        <input type="hidden" name="voyage_id" value="<?php echo $voyage_id; ?>">
        <input type="hidden" name="prix_total" value="<?php echo $prix_total; ?>">
        <input type="hidden" name="options_serialisees" value="<?php echo base64_encode(serialize($options)); ?>">
        <button type="submit">Ajouter au panier</button>
    </form>

    <!-- Paiement -->
    <form method="post" action="paiement.php">
        <input type="hidden" name="voyage_id" value="<?php echo $voyage_id; ?>">
        <input type="hidden" name="prix_total" value="<?php echo $prix_total; ?>">
        <input type="hidden" name="nb_personnes" value="<?php echo $nb_total_personnes; ?>">
        <input type="hidden" name="options_serialisees" value="<?php echo base64_encode(serialize($options)); ?>">
        <button type="submit">Confirmer et payer</button>
    </form>
</div>

</body>
</html>