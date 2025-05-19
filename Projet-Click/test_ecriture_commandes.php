<?php
// Activation des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Chemin du fichier à tester
$fichier = "data/commandes.json";

// Donnée test
$commande_test = [
    "utilisateur" => "test_user",
    "transaction" => uniqid(),
    "montant" => "123.45",
    "vendeur" => "MIM_A",
    "date" => date("Y-m-d H:i:s")
];

// Chargement et test d’écriture
$commandes = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $decoded = json_decode($contenu, true);
    if (is_array($decoded)) {
        $commandes = $decoded;
    } else {
        echo "<p style='color:red;'>❌ Fichier présent mais illisible ou vide</p>";
    }
} else {
    echo "<p style='color:orange;'>ℹ️ Fichier inexistant, il sera créé</p>";
}

$commandes[] = $commande_test;

// Tentative d’écriture
$ok = file_put_contents($fichier, json_encode($commandes, JSON_PRETTY_PRINT));

if ($ok === false) {
    echo "<p style='color:red;'>❌ Échec de l’écriture dans $fichier. Vérifie les permissions !</p>";
} else {
    echo "<p style='color:green;'>✅ Donnée écrite avec succès dans $fichier</p>";
}
?>