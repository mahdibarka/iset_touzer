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

// Ajouter ou modifier un département
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nom = $_POST['nom'];
    $directeur_id = $_POST['directeur_id'];

    if (!empty($nom) && !empty($directeur_id)) {
        try {
            if (!empty($id)) {
                // Modifier un département existant
                $sql = "UPDATE departement SET Nom = ?, Directeur_id = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $directeur_id, $id]);
                echo "<p style='color: green;'>Département modifié avec succès !</p>";
            } else {
                // Ajouter un nouveau département
                $sql = "INSERT INTO departement (Nom, Directeur_id) VALUES (?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $directeur_id]);
                echo "<p style='color: green;'>Département ajouté avec succès !</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Veuillez remplir tous les champs.</p>";
    }
}

// Supprimer un département
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    try {
        $sql = "DELETE FROM departement WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$delete_id]);
        echo "<p style='color: green;'>Département supprimé avec succès !</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
    }
}

// Récupérer les départements pour affichage
$departements = [];
try {
    $sql = "SELECT * FROM departement";
    $departements = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Départements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #343a40;
        }
        form {
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #dee2e6;
            text-align: left;
        }
        th {
            background-color: #e9ecef;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Départements</h1>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= $_GET['edit'] ?? '' ?>">
            <label for="nom">Nom du Département :</label>
            <input type="text" id="nom" name="nom" value="<?= $_GET['nom'] ?? '' ?>" required>
            <label for="directeur_id">ID du Directeur :</label>
            <input type="number" id="directeur_id" name="directeur_id" value="<?= $_GET['directeur_id'] ?? '' ?>" required>
            <button type="submit"><?= isset($_GET['edit']) ? 'Modifier' : 'Ajouter' ?></button>
        </form>
        <h2>Liste des Départements</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>ID Directeur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departements as $departement): ?>
                    <tr>
                        <td><?= $departement['id'] ?></td>
                        <td><?= $departement['Nom'] ?></td>
                        <td><?= $departement['Directeur_id'] ?></td>
                        <td class="actions">
                            <a href="?edit=<?= $departement['id'] ?>&nom=<?= $departement['Nom'] ?>&directeur_id=<?= $departement['Directeur_id'] ?>">Modifier</a>
                            <a href="?delete=<?= $departement['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce département ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
