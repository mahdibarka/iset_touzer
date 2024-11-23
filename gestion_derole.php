<?php
// Paramètres de connexion à la base de données
$host = '127.0.0.1';
$dbname = 'gestionuniversitaire';
$username = 'root';
$password = '';

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Ajouter ou modifier un rôle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nom = $_POST['nom'];

    if (!empty($nom)) {
        try {
            if (!empty($id)) {
                // Modifier un rôle existant
                $sql = "UPDATE role SET nom = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $id]);
                echo "<p style='color: green;'>Rôle modifié avec succès !</p>";
            } else {
                // Ajouter un nouveau rôle
                $sql = "INSERT INTO role (nom) VALUES (?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom]);
                echo "<p style='color: green;'>Rôle ajouté avec succès !</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Veuillez remplir le champ du nom du rôle.</p>";
    }
}

// Supprimer un rôle
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    try {
        $sql = "DELETE FROM role WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$delete_id]);
        echo "<p style='color: green;'>Rôle supprimé avec succès !</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
    }
}

// Récupérer les rôles pour affichage
$roles = [];
try {
    $sql = "SELECT * FROM role";
    $roles = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rôles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        form, table {
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            color: red;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Ajouter ou Modifier un Rôle</h1>
    <form method="POST" action="">
        <input type="hidden" id="id" name="id" value="<?= $_GET['edit'] ?? '' ?>">
        <label for="nom">Nom du Rôle :</label>
        <input type="text" id="nom" name="nom" value="<?= $_GET['nom'] ?? '' ?>" required>

        <button type="submit"><?= isset($_GET['edit']) ? 'Modifier' : 'Ajouter' ?></button>
    </form>

    <h1>Liste des Rôles</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roles as $role): ?>
                <tr>
                    <td><?= $role['id'] ?></td>
                    <td><?= $role['nom'] ?></td>
                    <td>
                        <a href="?edit=<?= $role['id'] ?>&nom=<?= $role['nom'] ?>">Modifier</a> |
                        <a href="?delete=<?= $role['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
