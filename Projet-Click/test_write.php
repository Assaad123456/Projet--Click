<?php
$commande = [
  "utilisateur" => "test_user",
  "transaction" => uniqid(),
  "montant" => "99.99",
  "vendeur" => "MIM_A",
  "date" => date("Y-m-d H:i:s")
];

$fichier = "data/commandes.json";
$commandes = file_exists($fichier) ? json_decode(file_get_contents($fichier), true) : [];

if (!is_array($commandes)) {
    $commandes = [];
}

$commandes[] = $commande;
file_put_contents($fichier, json_encode($commandes, JSON_PRETTY_PRINT));

echo "✅ Donnée test ajoutée à commandes.json";