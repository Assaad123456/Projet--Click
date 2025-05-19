<?php
session_start();
require_once("getapikey.php");

// Affichage des erreurs pour debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Récupérer les infos reçues de CY Bank
$transaction = $_GET['transaction'] ?? null;
$montant = $_GET['montant'] ?? null;
$vendeur = $_GET['vendeur'] ?? null;
$statut = $_GET['statut'] ?? null;
$control_recu = $_GET['control'] ?? null;

// Vérification minimale
if (!$transaction || !$montant || !$vendeur || !$statut || !$control_recu) {
    header("Location: paiement_erreur.php?erreur=parametres_manquants");
    exit;
}

// Calcul de la clé de contrôle
$api_key = getAPIKey($vendeur);
$control_attendu = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");

// Vérification d'intégrité
if ($control_recu !== $control_attendu) {
    header("Location: paiement_erreur.php?erreur=hash_invalide");
    exit;
}

// Si tout est OK et paiement accepté
if ($statut === "accepted") {
    $utilisateur = $_SESSION['login'] ?? "inconnu";
    $voyage_id = $_SESSION['voyage_id'] ?? null;

    if (!$voyage_id) {
        header("Location: paiement_erreur.php?erreur=voyage_id_manquant");
        exit;
    }

    $commande = [
        "utilisateur" => $utilisateur,
        "voyage_id" => $voyage_id,
        "transaction" => $transaction,
        "montant" => $montant,
        "vendeur" => $vendeur,
        "date" => date("Y-m-d H:i:s")
    ];

    $fichier_commandes = "data/commandes.json";
    $commandes = file_exists($fichier_commandes) ? json_decode(file_get_contents($fichier_commandes), true) : [];

    if (!is_array($commandes)) {
        $commandes = [];
    }

    $commandes[] = $commande;

    file_put_contents($fichier_commandes, json_encode($commandes, JSON_PRETTY_PRINT));

    header("Location: paiement_valide.php");
    exit;
} else {
    header("Location: paiement_erreur.php?erreur=paiement_refuse");
    exit;
}