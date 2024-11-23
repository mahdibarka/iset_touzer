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
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM departements WHERE id = $id";
    $result = $conn->query($sql);
    $departement = $result->fetch_assoc();
}

if (isset($_POST['edit_departement'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];

    $sql = "UPDATE departements SET nom='$nom', description='$description' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Département mis à jour avec succès !'); window.location.href='gestion_departements.php';</script>";
    } else {
        echo "<script>alert('Erreur : " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Département</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <div class="col-md-6 offset-md-3">
        <h3>Modifier Département</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du Département</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $departement['nom']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $departement['description']; ?></textarea>
            </div>
            <button type="submit" name="edit_departement" class="btn btn-primary">Sauvegarder</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
