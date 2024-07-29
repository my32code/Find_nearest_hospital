<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medecins</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 200px; /* Réduction de la taille de la barre latérale */
            background-color: #007bff;
            color: #fff;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px; /* Réduction de la taille de padding */
            width: 100%;
            text-decoration: none;
            color: #fff;
            transition: background-color 0.3s, transform 0.3s;
            margin: 5px 0; /* Réduction de l'espacement entre les éléments */
            flex-direction: column; /* Alignement des icônes et du texte */
        }
        .sidebar a:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }
        .sidebar a i {
            margin-right: 10px;
            font-size: 1.5em;
        }
        .sidebar .hopital-icon {
            font-size: 5em; /* Augmentation de la taille de l'icône de l'utilisateur */
            margin-bottom: 20px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f8f9fa;
            overflow-y: auto;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <span><i class="fas fa-hospital hopital-icon"></i></span></a> <!-- Icône d'hopital agrandie -->
        <a href="dashbord.php"><i class="fas fa-h-square"></i> Gestion des Hôpitaux</a>
        <a href="doctors.php"><i class="fas fa-user-md"></i> Médecins</a>
        <a href="patients.php"><i class="fas fa-procedures"></i> Patients</a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
    <div class="content">
        <div class="container">
            <h2>Medecins</h2>
            <a href="add_doctor.php" class="btn btn-primary">Ajouter un Médecin</a>
    <table class="table table-striped mt-3">
        <thead>
            <tr>
                <th></th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Hôpital</th>
                <th>Spécialité</th>
                <th>Téléphone</th>
                <th>Disponibilité</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connexion à la base de données
            $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

            // Récupération des médecins
            $stmt = $pdo->query("
                select * from patient
            ");
            $index = 1; // Initialisation de l'index pour la numérotation
            while ($row = $stmt->fetch()) {
                echo "<tr>
                    <td>{$index}</td> <!-- Utilisation de l'index pour la numérotation -->
                    <td>{$row['nom']}</td>
                    <td>{$row['prenom']}</td>
                    <td>{$row['hospital_name']}</td>
                    <td>{$row['speciality_name']}</td>
                    <td>{$row['numero']}</td>
                    <td>{$row['disponibility']}</td>
                    <td>
                        <a href='edit_doctor.php?id={$row['id_doc']}' class='btn btn-warning btn-sm'>Modifier</a>
                        <a href='delete_doctor.php?id={$row['id_doc']}' class='btn btn-danger btn-sm'>Supprimer</a>
                    </td>
                </tr>";
                $index++; // Incrémentation de l'index
            }
            
            ?>
        </tbody>
    </table>
        </div>  
    </div>

</body>
</html>