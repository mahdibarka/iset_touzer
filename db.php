<?php
$servername = "localhost";  // Hôte de la base de données
$username = "root";         // Nom d'utilisateur
$password = "";             // Mot de passe de l'utilisateur
$dbname = "gestionuniversitaire";  // Nom de la base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
?>
