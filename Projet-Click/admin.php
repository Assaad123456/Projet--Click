<?php
session_start();

// Vérifie que l'utilisateur est bien admin
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'admin') {
    echo "Accès refusé.";
    exit;
}

$fichier = "data/utilisateurs.json";
$utilisateurs = [];

if (file_exists($fichier)) {
    $contenu = file_get_contents($fichier);
    $utilisateurs = json_decode($contenu, true);
    if (!is_array($utilisateurs)) {
        $utilisateurs = [];
    }
}

$message = "";

// Suppression
if (isset($_GET['delete'])) {
    $login = $_GET['delete'];
    $utilisateurs = array_filter($utilisateurs, fn($u) => $u['login'] !== $login);
    $resultat = file_put_contents($fichier, json_encode(array_values($utilisateurs), JSON_PRETTY_PRINT));
    if ($resultat === false) {
        $message = "❌ Erreur : impossible d'écrire dans le fichier utilisateurs.json";
    } else {
        header("Location: admin.php?msg=supprime");
        exit;
    }
}

// Changement de rôle
if (isset($_GET['changerRole']) && isset($_GET['login'])) {
    foreach ($utilisateurs as &$u) {
        if ($u['login'] === $_GET['login']) {
            $u['role'] = ($u['role'] === 'admin') ? 'client' : 'admin';
            break;
        }
    }
    $resultat = file_put_contents($fichier, json_encode($utilisateurs, JSON_PRETTY_PRINT));
    if ($resultat === false) {
        $message = "❌ Erreur d’écriture lors du changement de rôle.";
    } else {
        header("Location: admin.php?msg=role");
        exit;
    }
}

// Recherche
$recherche = $_GET['q'] ?? '';
if ($recherche) {
    $utilisateurs = array_filter($utilisateurs, function ($u) use ($recherche) {
        return stripos($u['login'], $recherche) !== false
            || stripos($u['nom'], $recherche) !== false
            || stripos($u['prenom'], $recherche) !== false;
    });
}

// Pagination
$parPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$total = count($utilisateurs);
$totalPages = ceil($total / $parPage);
$debut = ($page - 1) * $parPage;
$affiches = array_slice($utilisateurs, $debut, $parPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin – Utilisateurs</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 90%; margin: auto; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #00509e; color: white; }
        h1, nav, form { text-align: center; }
        .pagination { text-align: center; margin-top: 20px; }
        .pagination a {
            padding: 6px 10px;
            margin: 0 4px;
            text-decoration: none;
            background-color: #eee;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #00509e;
            color: white;
        }
        .btn { padding: 4px 8px; margin: 2px; border: none; cursor: pointer; border-radius: 4px; }
        .delete { background-color: #d9534f; color: white; }
        .toggle { background-color: #0275d8; color: white; }
        .message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 10px;
            color: #155724;
            width: 50%;
            margin: 15px auto;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Administration – Utilisateurs</h1>

<nav>
    <a href="index.php">Accueil</a> |
    <a href="profil.php">Profil</a> |
    <a href="logout.php">Déconnexion</a>
</nav>

<?php if (isset($_GET['msg'])): ?>
    <div class="message">
        <?php if ($_GET['msg'] === 'supprime') echo "✅ Utilisateur supprimé avec succès."; ?>
        <?php if ($_GET['msg'] === 'role') echo "✅ Rôle mis à jour."; ?>
    </div>
<?php elseif ($message): ?>
    <div class="message" style="background:#f8d7da;color:#721c24;border-color:#f5c6cb;">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<form method="get">
    <input type="text" name="q" placeholder="Recherche..." value="<?php echo htmlspecialchars($recherche); ?>">
    <button type="submit">Rechercher</button>
</form>

<table>
    <thead>
        <tr>
            <th>Login</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Rôle</th>
            <th>Date naissance</th>
            <th>Adresse</th>
            <th>Inscription</th>
            <th>Connexion</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($affiches as $u): ?>
            <tr>
                <td><?php echo htmlspecialchars($u['login']); ?></td>
                <td><?php echo htmlspecialchars($u['nom']); ?></td>
                <td><?php echo htmlspecialchars($u['prenom']); ?></td>
                <td><?php echo htmlspecialchars($u['role']); ?></td>
                <td><?php echo htmlspecialchars($u['naissance']); ?></td>
                <td><?php echo htmlspecialchars($u['adresse']); ?></td>
                <td><?php echo htmlspecialchars($u['date_inscription']); ?></td>
                <td><?php echo htmlspecialchars($u['derniere_connexion']); ?></td>
                <td>
                    <?php if ($u['login'] !== $_SESSION['utilisateur']['login']): ?>
                        <a href="?changerRole=1&login=<?php echo urlencode($u['login']); ?>" class="btn toggle">Basculer rôle</a>
                        <a href="?delete=<?php echo urlencode($u['login']); ?>" class="btn delete" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                    <?php else: ?>
                        <em>Moi</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"
           class="<?php echo ($i === $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
</div>

</body>
</html>

