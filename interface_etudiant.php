<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        form { display: flex; gap: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Gestion des Étudiants</h1>

    <!-- Formulaire pour ajouter ou modifier un étudiant -->
    <form id="studentForm">
        <input type="hidden" id="student_id" value="">
        <input type="text" id="first_name" placeholder="Prénom" required>
        <input type="text" id="last_name" placeholder="Nom" required>
        <input type="number" id="class_id" placeholder="ID Classe" required>
        <button type="submit">Ajouter / Mettre à jour</button>
    </form>

    <!-- Tableau pour afficher les étudiants -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Classe</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="studentTable">
            <!-- Contenu dynamique -->
        </tbody>
    </table>

    <script>
        const apiUrl = 'students.php';

        // Fonction pour charger les étudiants
        async function loadStudents() {
            const response = await fetch(apiUrl);
            const students = await response.json();
            const table = document.getElementById('studentTable');
            table.innerHTML = students.map(student => `
                <tr>
                    <td>${student.id}</td>
                    <td>${student.first_name}</td>
                    <td>${student.last_name}</td>
                    <td>${student.class_name}</td>
                    <td>
                        <button onclick="editStudent(${student.id}, '${student.first_name}', '${student.last_name}', ${student.class_id})">Modifier</button>
                        <button onclick="deleteStudent(${student.id})">Supprimer</button>
                    </td>
                </tr>
            `).join('');
        }

        // Fonction pour remplir le formulaire pour modifier un étudiant
        function editStudent(id, firstName, lastName, classId) {
            document.getElementById('student_id').value = id;
            document.getElementById('first_name').value = firstName;
            document.getElementById('last_name').value = lastName;
            document.getElementById('class_id').value = classId;
        }

        // Fonction pour ajouter ou mettre à jour un étudiant
        document.getElementById('studentForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('student_id').value;
            const first_name = document.getElementById('first_name').value;
            const last_name = document.getElementById('last_name').value;
            const class_id = document.getElementById('class_id').value;

            const action = id ? 'update' : 'add';

            await fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=${action}&id=${id}&first_name=${first_name}&last_name=${last_name}&class_id=${class_id}`
            });

            document.getElementById('studentForm').reset();
            loadStudents();
        });

        // Fonction pour supprimer un étudiant
        async function deleteStudent(id) {
            await fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete&id=${id}`
            });
            loadStudents();
        }

        // Charger les étudiants au démarrage
        loadStudents();
    </script>
</body>
</html>
