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

// Ajouter ou modifier un utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $departement = $_POST['departement'];
    $role_id = $_POST['role_id'];
    $dr_general = isset($_POST['dr_general']) ? 1 : 0;

    if (!empty($nom) && !empty($prenom) && !empty($departement)) {
        try {
            if (!empty($id)) {
                // Modifier un utilisateur existant
                $sql = "UPDATE utilisateur SET nom = ?, prenom = ?, departement = ?, role_id = ?, dr_general = ? WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $prenom, $departement, $role_id, $dr_general, $id]);
                echo "<p style='color: green;'>Utilisateur modifié avec succès !</p>";
            } else {
                // Ajouter un nouveau utilisateur
                $sql = "INSERT INTO utilisateur (nom, prenom, departement, role_id, dr_general) VALUES (?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$nom, $prenom, $departement, $role_id, $dr_general]);
                echo "<p style='color: green;'>Utilisateur ajouté avec succès !</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Veuillez remplir tous les champs.</p>";
    }
}

// Supprimer un utilisateur
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    try {
        $sql = "DELETE FROM utilisateur WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$delete_id]);
        echo "<p style='color: green;'>Utilisateur supprimé avec succès !</p>";
    } catch (PDOException $e) {
        echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
    }
}

// Récupérer les utilisateurs pour affichage
$utilisateurs = [];
try {
    $sql = "SELECT u.id, u.nom, u.prenom, u.departement, u.dr_general, r.nom AS role
            FROM utilisateur u
            JOIN role r ON u.role_id = r.id";
    $utilisateurs = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
}

// Récupérer les rôles pour le formulaire
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
    <title>Gestion des Utilisateurs</title>
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
            max-width: 900px;
            margin: 20px auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input[type="text"], select, button {
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
    <h1>Ajouter ou Modifier un Utilisateur</h1>
    <form method="POST" action="">
        <input type="hidden" id="id" name="id" value="<?= $_GET['edit'] ?? '' ?>">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= $_GET['nom'] ?? '' ?>" required>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" value="<?= $_GET['prenom'] ?? '' ?>" required>

        <label for="departement">Département :</label>
        <input type="text" id="departement" name="departement" value="<?= $_GET['departement'] ?? '' ?>" required>

        <label for="role_id">Rôle :</label>
        <select name="role_id" required>
            <option value="">Sélectionner un rôle</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id'] ?>" <?= (isset($_GET['role_id']) && $_GET['role_id'] == $role['id']) ? 'selected' : '' ?>>
                    <?= $role['nom'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="dr_general">Docteur Général :</label>
        <input type="checkbox" name="dr_general" <?= isset($_GET['dr_general']) && $_GET['dr_general'] ? 'checked' : '' ?>>

        <button type="submit"><?= isset($_GET['edit']) ? 'Modifier' : 'Ajouter' ?></button>
    </form>

    <h1>Liste des Utilisateurs</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Département</th>
                <th>Rôle</th>
                <th>Dr Général</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
                <tr>
                    <td><?= $utilisateur['id'] ?></td>
                    <td><?= $utilisateur['nom'] ?></td>
                    <td><?= $utilisateur['prenom'] ?></td>
                    <td><?= $utilisateur['departement'] ?></td>
                    <td><?= $utilisateur['role'] ?></td>
                    <td><?= $utilisateur['dr_general'] == 1 ? 'Oui' : 'Non' ?></td>
                    <td>
                        <a href="?edit=<?= $utilisateur['id'] ?>&nom=<?= $utilisateur['nom'] ?>&prenom=<?= $utilisateur['prenom'] ?>&departement=<?= $utilisateur['departement'] ?>&role_id=<?= $utilisateur['role_id'] ?>&dr_general=<?= $utilisateur['dr_general'] ?>">Modifier</a> |
                        <a href="?delete=<?= $utilisateur['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
