<?php
// Connexion à la base de données
$host = '127.0.0.1'; // Hôte de la base de données
$dbname = 'gestionuniversitaire'; // Nom de la base de données
$username = 'root'; // Nom d'utilisateur
$password = ''; // Mot de passe (s'il y en a un)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Échec de la connexion à la base de données : " . $e->getMessage());
}

// Insertion d'une absence
if (isset($_POST['submit'])) {
    $etudiant_id = $_POST['etudiant_id'];
    $emploi_id = $_POST['emploi_id'];
    $date = $_POST['date'];
    $justifie = $_POST['justifie'];
    $motif = $_POST['motif'];

    $sql = "INSERT INTO absence (etudiant_id, Emploi_id, date, justifie, motif) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$etudiant_id, $emploi_id, $date, $justifie, $motif]);
    echo "Donnée insérée avec succès !<br>";
}

// Récupérer les absences
$sql = "SELECT * FROM absence";
$stmt = $pdo->query($sql);
$absences = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Absences</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
    /* Basic Reset */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
    }

    body {
        display: flex;
        min-height: 100vh;
        background-color: #f0f4f7;
        color: #333;
        font-size: 16px;
    }

    /* Sidebar Styling */
    .sidebar {
        width: 250px;
        background-color: #00796b;
        padding: 20px;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2.logo {
        font-size: 1.5em;
        text-align: center;
        margin-bottom: 30px;
        color: #ffffff;
        border-bottom: 2px solid #ffffff;
        padding-bottom: 10px;
    }

    .sidebar ul {
        list-style-type: none;
        width: 100%;
        padding: 0;
    }

    .sidebar ul li {
        margin: 10px 0;
    }

    .sidebar ul li a {
        text-decoration: none;
        color: #e0f2f1;
        font-weight: bold;
        padding: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .sidebar ul li a:hover {
        background-color: #004d40;
    }

    /* Main Content Styling */
    .main-content {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .main-content header {
        background-color: #00796b;
        color: white;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 20px;
    }

    .main-content h1 {
        font-size: 1.8em;
    }

    /* Section Styling */
    .section {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .section h2 {
        font-size: 1.6em;
        color: #00695c;
        margin-bottom: 15px;
        text-align: center;
    }

    /* Form Styling */
    .form-container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .form-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-container label,
    .form-container input,
    .form-container select {
        font-size: 1em;
        padding: 12px;
        margin: 5px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        outline: none;
    }

    .form-container input:focus,
    .form-container select:focus {
        border-color: #00796b;
    }

    .form-container button {
        padding: 12px;
        background-color: #00796b;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .form-container button:hover {
        background-color: #004d40;
    }

    /* Table Styling */
    .table-container {
        overflow-x: auto;
        margin-top: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #00796b;
        color: white;
    }

    table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    table tr:hover {
        background-color: #ddd;
    }

    .management-links {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
        margin-top: 10px;
    }

    .management-links li {
        list-style-type: none;
        flex: 1 1 200px;
        background-color: #e0f2f1;
        border: 1px solid #b2dfdb;
        border-radius: 8px;
        text-align: center;
        padding: 15px;
        transition: background-color 0.3s, transform 0.3s;
    }

    .management-links li:hover {
        background-color: #b2dfdb;
        transform: scale(1.05);
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        body {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            height: auto;
            padding: 15px;
        }

        .main-content {
            padding: 15px;
        }

        .form-container form {
            gap: 12px;
        }

        .sidebar ul li a {
            font-size: 14px;
        }

        table th, table td {
            font-size: 14px;
            padding: 10px;
        }

        .form-container button {
            padding: 10px;
        }

        .management-links li {
            flex: 1 1 100%;
            margin-bottom: 15px;
        }
    }

    @media (max-width: 480px) {
        .sidebar {
            padding: 10px;
        }

        .form-container form {
            gap: 10px;
        }

        .form-container label,
        .form-container input,
        .form-container select {
            font-size: 0.9em;
            padding: 10px;
        }

        table th, table td {
            font-size: 12px;
            padding: 8px;
        }
    }
</style>

</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="logo">Dashboard</h2>
        <ul>
            <li><a href="gestion_absences_dashboard.php"><i class="fas fa-user-check"></i> Gestion des Absences</a></li>
            <li><a href="gestion_departements.php"><i class="fas fa-building"></i> Gestion des Départements</a></li>
            <li><a href="gestion_enseignants.php"><i class="fas fa-chalkboard-teacher"></i> Gestion des Enseignants</a></li>
            <li><a href="gestion_etudiants.php"><i class="fas fa-user-graduate"></i> Gestion des Étudiants</a></li>
            <li><a href="gestion_emploidutemps.php"><i class="fas fa-calendar-alt"></i> Gestion des Emplois du Temps</a></li>
            <li><a href="gestion_derole.php" target="_blank"><i class="fas fa-calendar-alt"></i> Gestion des Rôles</a></li>
            <li><a href="gestion_affectationcours.php" target="_blank"><i class="fas fa-calendar-alt"></i> Gestion Affectation Cours</a></li>
            <li><a href="gestion_logmodification.php" target="_blank"><i class="fas fa-calendar-alt"></i> Gestion Log Modifications</a></li>
            <li><a href="cercle.php" target="_blank"><i class="fas fa-calendar-alt"></i> Gestion Utilisateur</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Gestion des Absences</h1>
        </header>

        <section class="section">
            <h2>Ajouter une Absence</h2>
            <div class="form-container">
                <form action="gestion_absences_dashboard" method="POST">
                    <label for="etudiant_id">Étudiant :</label>
                    <select name="etudiant_id" id="etudiant_id">
                        <!-- Dynamic student options -->
                    </select>
                    <label for="emploi_id">Emploi :</label>
                    <select name="emploi_id" id="emploi_id">
                        <!-- Dynamic emploi options -->
                    </select>
                    <label for="date">Date :</label>
                    <input type="date" name="date" id="date" required>
                    <label for="justifie">Justifié :</label>
                    <input type="checkbox" name="justifie" id="justifie">
                    <label for="motif">Motif :</label>
                    <input type="text" name="motif" id="motif">
                    <button type="submit" name="submit">Enregistrer</button>
                </form>
            </div>
        </section>

        <section class="section">
            <h2>Liste des Absences</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nom Étudiant</th>
                            <th>Emploi</th>
                            <th>Date</th>
                            <th>Justifié</th>
                            <th>Motif</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($absences as $absence) : ?>
                            <tr>
                                <td><?php echo $absence['etudiant_id']; ?></td>
                                <td><?php echo $absence['emploi_id']; ?></td>
                                <td><?php echo $absence['date']; ?></td>
                                <td><?php echo $absence['justifie'] ? 'Oui' : 'Non'; ?></td>
                                <td><?php echo $absence['motif']; ?></td>
                                <td>
                                    <a href="modifier_absence.php?id=<?php echo $absence['id']; ?>">Modifier</a> |
                                    <a href="supprimer_absence.php?id=<?php echo $absence['id']; ?>">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>

</html>
