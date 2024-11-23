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
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            background-color: #f4f7fa;
            color: #333;
            font-size: 16px;
        }

        .sidebar {
            width: 250px;
            background-color: #00695c;
            padding: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
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

        .main-content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
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
            gap: 10px;
        }

        .form-container label,
        .form-container input,
        .form-container select {
            font-size: 1em;
            padding: 10px;
            margin: 5px 0;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            <li ><a href="gestion_derole.php" target="_blank" ><i class="fas fa-calendar-alt"></i> gestion des Rôles </a></li>
            <li ><a href="gestion_affectationcours.php" target="_blank" ><i class="fas fa-calendar-alt"></i> gestion_affectationcours </a></li>        
            <li ><a href="gestion_logmodification.php" target="_blank" ><i class="fas fa-calendar-alt"></i> gestion_logmodification </a></li>  
            <li ><a href="cercle.php" target="_blank" ><i class="fas fa-calendar-alt"></i> gestion_utlisateur </a></li>        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Gestion des Absences</h1>
        </header>

        <section class="section">
            <h2>Ajouter une Absence</h2>
            <div class="form-container">
                <form action="gestion_absences_dashboard.php" method="POST">
                    <label for="etudiant_id">ID Étudiant :</label>
                    <input type="number" id="etudiant_id" name="etudiant_id" required>

                    <label for="emploi_id">ID Emploi :</label>
                    <input type="number" id="emploi_id" name="emploi_id" required>

                    <label for="date">Date :</label>
                    <input type="date" id="date" name="date" required>

                    <label for="justifie">Justifié :</label>
                    <select id="justifie" name="justifie" required>
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>

                    <label for="motif">Motif :</label>
                    <input type="text" id="motif" name="motif" required>

                    <input type="submit" name="submit" value="Ajouter Absence">
                </form>
            </div>
        </section>

        <section class="section">
            <h2>Liste des Absences</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ID Étudiant</th>
                            <th>ID Emploi</th>
                            <th>Date</th>
                            <th>Justifié</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($absences as $absence): ?>
                            <tr>
                                <td><?php echo $absence['id']; ?></td>
                                <td><?php echo $absence['etudiant_id']; ?></td>
                                <td><?php echo $absence['Emploi_id']; ?></td>
                                <td><?php echo $absence['date']; ?></td>
                                <td><?php echo $absence['justifie'] ? 'Oui' : 'Non'; ?></td>
                                <td><?php echo $absence['motif']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section">
            <h2>Actions de Gestion</h2>
            <ul class="management-links">
                <li><a href="supprimer_absence.php"><i class="fas fa-trash-alt"></i> Supprimer une Absence</a></li>
                <li><a href="modifier_absence.php"><i class="fas fa-edit"></i> Modifier une Absence</a></li>
            </ul>
        </section>
    </div>
</body>

</html>
