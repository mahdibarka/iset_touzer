<?php
// Connexion à la base de données
$host = '127.0.0.1';
$dbname = 'gestionuniversitaire';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion à la base de données : " . $e->getMessage());
}

// Récupérer les logs de modification
$logs_sql = "SELECT l.id, l.action, l.date_modification, u.nom AS utilisateur_nom 
             FROM log_modification l
             JOIN utilisateur u ON l.utilisateur_id = u.id
             ORDER BY l.date_modification DESC";
$logs_stmt = $pdo->query($logs_sql);
$logs = $logs_stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Logs de Modifications</title>
</head>
<body>
    <h1>Gestion des Logs de Modifications</h1>

    <!-- Affichage des logs de modifications -->
    <h2>Liste des Logs de Modifications</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Action</th>
            <th>Date de Modification</th>
            <th>Utilisateur</th>
        </tr>
        <?php if (isset($logs) && count($logs) > 0): ?>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo $log['id']; ?></td>
                    <td><?php echo $log['action']; ?></td>
                    <td><?php echo $log['date_modification']; ?></td>
                    <td><?php echo $log['utilisateur_nom']; ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Aucun log de modification trouvé.</td></tr>
        <?php endif; ?>
    </table>

</body>
</html>
