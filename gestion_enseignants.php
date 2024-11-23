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

// Insertion d'un enseignant
if (isset($_POST['add_enseignant'])) {
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $departement_id = $_POST['departement_id'];

    $sql = "INSERT INTO enseignants (cin, nom, prenom, departement_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cin, $nom, $prenom, $departement_id]);
    echo "Enseignant ajouté avec succès !<br>";
}

// Modification d'un enseignant
if (isset($_POST['update_enseignant'])) {
    $id = $_POST['id'];
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $departement_id = $_POST['departement_id'];

    $sql = "UPDATE enseignants SET cin = ?, nom = ?, prenom = ?, departement_id = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cin, $nom, $prenom, $departement_id, $id]);
    echo "Enseignant modifié avec succès !<br>";
}

// Récupérer la liste des enseignants
$sql = "SELECT enseignants.*, departements.nom AS departement_nom 
        FROM enseignants 
        JOIN departements ON enseignants.departement_id = departements.id";
$stmt = $pdo->query($sql);
$enseignants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer la liste des départements
$sql = "SELECT * FROM departements";
$stmt = $pdo->query($sql);
$departements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Enseignants</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        body {
            display: flex;
            background-color: #f4f7fa;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            background-color: #1976d2;
            padding: 20px;
            color: white;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: white;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #1565c0;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        header {
            background-color: #1976d2;
            color: white;
            text-align: center;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        section {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #1976d2;
            color: white;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form label, form input, form select, form button {
            font-size: 1em;
            padding: 10px;
            margin: 5px 0;
        }

        button {
            background-color: #1976d2;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #1565c0;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="gestion_absences_dashboard.php"><i class="fas fa-user-check"></i> Gestion des Absences</a></li>
            <li><a href="gestion_departements.php"><i class="fas fa-building"></i> Gestion des Départements</a></li>
            <li><a href="gestion_enseignants.php"><i class="fas fa-chalkboard-teacher"></i> Gestion des Enseignants</a></li>
            <li><a href="gestion_etudiants.php"><i class="fas fa-user-graduate"></i> Gestion des Étudiants</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Gestion des Enseignants</h1>
        </header>

        <section>
            <h2>Ajouter un Enseignant</h2>
            <form method="POST">
                <label for="cin">CIN :</label>
                <input type="text" name="cin" id="cin" required>

                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" required>

                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" required>

                <label for="departement_id">Département :</label>
                <select name="departement_id" id="departement_id" required>
                    <?php foreach ($departements as $departement): ?>
                        <option value="<?= $departement['id']; ?>"><?= $departement['nom']; ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="add_enseignant">Ajouter</button>
            </form>
        </section>

        <section>
            <h2>Modifier un Enseignant</h2>
            <form method="POST">
                <label for="id">ID Enseignant :</label>
                <input type="number" name="id" id="id" required>

                <label for="cin">CIN :</label>
                <input type="text" name="cin" id="cin" required>

                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" required>

                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" required>

                <label for="departement_id">Département :</label>
                <select name="departement_id" id="departement_id" required>
                    <?php foreach ($departements as $departement): ?>
                        <option value="<?= $departement['id']; ?>"><?= $departement['nom']; ?></option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="update_enseignant">Modifier</button>
            </form>
        </section>

        <section>
            <h2>Liste des Enseignants</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>CIN</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Département</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enseignants as $enseignant): ?>
                        <tr>
                            <td><?= $enseignant['id']; ?></td>
                            <td><?= $enseignant['cin']; ?></td>
                            <td><?= $enseignant['nom']; ?></td>
                            <td><?= $enseignant['prenom']; ?></td>
                            <td><?= $enseignant['departement_nom']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>
