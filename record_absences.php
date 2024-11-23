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

// Récupérer les classes, matières et départements
$classes = mysqli_query($conn, "SELECT * FROM classes");
$subjects = mysqli_query($conn, "SELECT * FROM subjects");
$departments = mysqli_query($conn, "SELECT * FROM departments");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $date = $_POST['date'];
    $reason = $_POST['reason'];

    // Insérer les absences dans la table absences
    $query = "INSERT INTO absences (student_id, date, reason) VALUES ('$student_id', '$date', '$reason')";
    if (mysqli_query($conn, $query)) {
        echo "<p>Absence enregistrée avec succès!</p>";
    } else {
        echo "<p>Erreur d'enregistrement de l'absence!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement des Absences</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Enregistrer l'Absence d'un Étudiant</h2>
        <form method="POST">
            <label for="class_id">Classe:</label>
            <select id="class_id" name="class_id" required>
                <option value="">Sélectionner une Classe</option>
                <?php while($row = mysqli_fetch_assoc($classes)): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['class_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="subject_id">Matière:</label>
            <select id="subject_id" name="subject_id" required>
                <option value="">Sélectionner une Matière</option>
                <?php while($row = mysqli_fetch_assoc($subjects)): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['subject_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="department_id">Département:</label>
            <select id="department_id" name="department_id" required>
                <option value="">Sélectionner un Département</option>
                <?php while($row = mysqli_fetch_assoc($departments)): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['department_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label for="student_id">Étudiant:</label>
            <select id="student_id" name="student_id" required>
                <option value="">Sélectionner un Étudiant</option>
                <?php
                    // Récupérer les étudiants en fonction de la classe et du département
                    if(isset($_POST['class_id'])) {
                        $class_id = $_POST['class_id'];
                        $department_id = $_POST['department_id'];
                        $query = "SELECT * FROM students WHERE class_id = '$class_id' AND department_id = '$department_id'";
                        $students = mysqli_query($conn, $query);

                        while($row = mysqli_fetch_assoc($students)) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                    }
                ?>
            </select>

            <label for="date">Date d'Absence:</label>
            <input type="date" id="date" name="date" required>

            <label for="reason">Motif de l'Absence:</label>
            <input type="text" id="reason" name="reason" required>

            <button type="submit">Enregistrer l'Absence</button>
        </form>
    </div>
</body>
</html>
