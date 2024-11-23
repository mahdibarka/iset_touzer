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

// Récupérer les informations de l'étudiant (en supposant que l'étudiant est déjà authentifié)
$etudiant_id = 1; // Ceci devrait être récupéré dynamiquement (par exemple, depuis une session)

function fetchTableData($pdo, $table, $etudiant_id) {
    $sql = "SELECT * FROM $table WHERE etudiant_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$etudiant_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$emplois = fetchTableData($pdo, 'emplois', $etudiant_id);
$absences = fetchTableData($pdo, 'absence', $etudiant_id);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Étudiant</title>
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
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="logo">Espace Étudiant</h2>
        <ul>
            <li><a href="emploi_du_temps.php"><i class="fas fa-calendar-alt"></i> Emploi du Temps</a></li>
            <li><a href="absences.php"><i class="fas fa-times-circle"></i> Consulter Mes Absences</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Bienvenue, Étudiant</h1>
        </header>

        <section class="section">
            <h2>Mon Emploi du Temps</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th>Heure</th>
                            <th>Emploi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emplois as $emploi): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($emploi['jour']); ?></td>
                                <td><?php echo htmlspecialchars($emploi['heure']); ?></td>
                                <td><?php echo htmlspecialchars($emploi['cours']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="section">
            <h2>Mes Absences</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Justifié</th>
                            <th>Motif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($absences as $absence): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($absence['date']); ?></td>
                                <td><?php echo $absence['justifie'] ? 'Oui' : 'Non'; ?></td>
                                <td><?php echo htmlspecialchars($absence['motif']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</body>

</html>
