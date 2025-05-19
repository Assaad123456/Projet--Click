<?php
session_start();

// Ajouter au panier si le formulaire est soumis (POST) avec options
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['options']) && isset($_POST['voyage_id'])) {
    $voyage_id = $_POST['voyage_id'];
    $options = $_POST['options'];

    // Charger les voyages
    $voyages = json_decode(file_get_contents("data/voyages.json"), true);
    $voyage_panier = null;
    foreach ($voyages as $v) {
        if ($v['id'] == $voyage_id) {
            $voyage_panier = $v;
            break;
        }
    }

    if ($voyage_panier) {
        $prix_total = floatval($voyage_panier['prix']);

        // Ajouter seulement si ce voyage n'est pas déjà dans le panier
        $deja_dedans = false;
        foreach ($_SESSION['panier'] ?? [] as $item) {
            if ($item['voyage']['id'] == $voyage_id) {
                $deja_dedans = true;
                break;
            }
        }

        if (!$deja_dedans) {
            $_SESSION['panier'][] = [
                'voyage' => $voyage_panier,
                'options' => $options,
                'prix_total' => $prix_total
            ];
        }
    }
}

// ✅ Correction ici : accepter ?id=2 ou ?voyage_id=2
$id = $_GET['voyage_id'] ?? $_GET['id'] ?? null;

if (!$id) {
    echo "Aucun voyage sélectionné.";
    exit;
}

// Charger les données du voyage
$fichier = "data/voyages.json";
$voyages = json_decode(file_get_contents($fichier), true);

$voyage = null;
foreach ($voyages as $v) {
    if ($v['id'] == $id) {
        $voyage = $v;
        break;
    }
}

if (!$voyage) {
    echo "Voyage introuvable.";
    exit;
}

// ✅ Enregistrer en session l’ID du voyage (pour retour_paiement.php)
$_SESSION['voyage_id'] = $voyage['id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails – <?php echo htmlspecialchars($voyage['titre']); ?></title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        h1, h2 { text-align: center; }
        form { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        .etape { margin-bottom: 30px; padding: 10px; border-bottom: 1px solid #ddd; }
        label { display: block; margin: 6px 0; }
        select, input[type="number"] { width: 100%; padding: 5px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; }
    </style>
</head>
<body>

<h1><?php echo htmlspecialchars($voyage['titre']); ?></h1>
<p style="text-align:center;">Dates : <?php echo $voyage['date_debut']; ?> → <?php echo $voyage['date_fin']; ?></p>

<form method="post" action="recapitulatif.php">
    <input type="hidden" name="voyage_id" value="<?php echo $voyage['id']; ?>">

    <?php foreach ($voyage['etapes'] as $index => $etape): ?>
        <div class="etape">
            <h2>Étape : <?php echo htmlspecialchars($etape['titre']); ?></h2>
            <p><strong>Lieu :</strong> <?php echo htmlspecialchars($etape['lieu']); ?></p>

            <?php if (isset($etape['hebergements'])): ?>
                <label for="hebergement_<?php echo $index; ?>">Hébergement :</label>
                <select name="options[<?php echo $index; ?>][hebergement]">
                    <?php foreach ($etape['hebergements'] as $h): ?>
                        <option value="<?php echo $h['titre']; ?>">
                            <?php echo $h['titre']; ?> (<?php echo $h['prix']; ?> €/pers.)
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <?php if (isset($etape['activites'])): ?>
                <label>Activités :</label>
                <?php foreach ($etape['activites'] as $i => $a): ?>
                    <input type="checkbox" name="options[<?php echo $index; ?>][activites][<?php echo $i; ?>][choisi]" value="1">
                    <?php echo $a['titre']; ?> (<?php echo $a['prix']; ?> €)
                    <label>Nombre de personnes :</label>
                    <input type="number" name="options[<?php echo $index; ?>][activites][<?php echo $i; ?>][nb]" value="1" min="1">
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (isset($etape['restauration'])): ?>
                <label for="restauration_<?php echo $index; ?>">Restauration :</label>
                <select name="options[<?php echo $index; ?>][restauration]">
                    <?php foreach ($etape['restauration'] as $r): ?>
                        <option value="<?php echo $r['titre']; ?>">
                            <?php echo $r['titre']; ?> (<?php echo $r['prix']; ?> €/jour)
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <div style="text-align: center;">
        <button type="submit">Réserver / Voir le récapitulatif</button>
    </div>
</form>

</body>
</html>