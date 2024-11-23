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

// Requête pour obtenir les utilisateurs et leurs informations
$sql = "SELECT u.id, u.nom, u.prenom, u.departement, u.role FROM utilisateur u";
try {
    $utilisateurs = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informations sous forme de cercles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .circle-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 14px;
            color: white;
            font-weight: bold;
            padding: 10px;
            transition: background-color 0.3s;
        }

        /* Couleurs dynamiques en fonction du rôle */
        .role-administrateur { background-color: #e74c3c; }  /* Red */
        .role-professeur { background-color: #3498db; }     /* Blue */
        .role-etudiant { background-color: #2ecc71; }       /* Green */
        .role-assistante { background-color: #f39c12; }     /* Yellow */
        .role-chercheur { background-color: #9b59b6; }      /* Purple */
        .role-default { background-color: #e67e22; }        /* Orange */

        .circle span {
            display: block;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <h1>Liste des Utilisateurs sous forme de cercles colorés</h1>

    <div class="circle-container">
        <?php
        // Boucle pour afficher chaque utilisateur avec un cercle coloré en fonction du rôle
        foreach ($utilisateurs as $utilisateur) {
            $role = strtolower($utilisateur['role']); // Récupérer le rôle en minuscules pour le choix de couleur
            $circleClass = 'role-default'; // Valeur par défaut si le rôle ne correspond à rien

            // Définir la couleur du cercle en fonction du rôle
            switch ($role) {
                case 'administrateur':
                    $circleClass = 'role-administrateur';
                    break;
                case 'professeur':
                    $circleClass = 'role-professeur';
                    break;
                case 'etudiant':
                    $circleClass = 'role-etudiant';
                    break;
                case 'assistante':
                    $circleClass = 'role-assistante';
                    break;
                case 'chercheur':
                    $circleClass = 'role-chercheur';
                    break;
            }
            ?>
            <div class="circle <?= $circleClass ?>">
                <div>
                    <?= $utilisateur['prenom'] ?> <br> <?= $utilisateur['nom'] ?>
                    <span>Département: <?= $utilisateur['departement'] ?></span>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
