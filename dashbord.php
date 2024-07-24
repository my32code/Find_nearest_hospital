<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            width: 250px;
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
            padding: 15px;
            width: 100%;
            text-decoration: none;
            color: #fff;
            transition: background-color 0.3s, transform 0.3s;
            margin: 10px 0;
        }
        .sidebar a:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }
        .sidebar a i {
            margin-right: 10px;
            font-size: 1.5em;
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
        <a href="dashbord.php"><i class="fas fa-hospital"></i> Gestion des Hôpitaux</a>
        <a href="doctors.php"><i class="fas fa-user-md"></i> Médecins</a>
        <a href="patients.php"><i class="fas fa-procedures"></i> Patients</a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
    <div class="content">
        <div class="container">
            <h2>Gestion des Hôpitaux</h2>
            <a href="add_hospital.php" class="btn btn-primary">Ajouter un Hôpital</a>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connexion à la base de données
                    $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
                    
                    // Récupération des hôpitaux
                    $stmt = $pdo->query("SELECT * FROM hopital JOIN adress ON hopital.id_ad = adress.id_ad");
                    while ($row = $stmt->fetch()) {
                        echo "
                        <tr>
                            <td>{$row['id_hpt']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['latitude']}</td>
                            <td>{$row['longitude']}</td>
                            <td>{$row['ville']}, {$row['commune']}, {$row['arrondissement']}</td>
                            <td>{$row['numero']}</td>
                            <td>
                                <a href='edit_hospital.php?id={$row['id_hpt']}' class='btn btn-warning btn-sm'>Modifier</a>
                                <a href='delete_hospital.php?id={$row['id_hpt']}' class='btn btn-danger btn-sm'>Supprimer</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="container">
            <h2>Médecins</h2>
            <a href="add_doctor.php" class="btn btn-primary">Ajouter un Médecin</a>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Hôpital</th>
                        <th>Spécialité</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connexion à la base de données
                    $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
                    
                    // Récupération des médecins
                    $stmt = $pdo->query("
                        SELECT docteur.*, hopital.nom AS hospital_name, speciality.libelle AS speciality_name 
                        FROM docteur 
                        JOIN hopital ON docteur.id_hpt = hopital.id_hpt 
                        JOIN speciality ON docteur.id_sp = speciality.id_sp
                    ");
                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                            <td>{$row['id_doc']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['prenom']}</td>
                            <td>{$row['hospital_name']}</td>
                            <td>{$row['speciality_name']}</td>
                            <td>{$row['numero']}</td>
                            <td>
                                <a href='edit_doctor.php?id={$row['id_doc']}' class='btn btn-warning btn-sm'>Modifier</a>
                                <a href='delete_doctor.php?id={$row['id_doc']}' class='btn btn-danger btn-sm'>Supprimer</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="container">
            <h2>Patients</h2>
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Hôpital</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Connexion à la base de données
                    $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
                    
                    // Récupération des patients
                    $stmt = $pdo->query("
                        SELECT patient.*, hopital.nom AS hospital_name 
                        FROM patient 
                        JOIN hopital ON patient.id_hpt = hopital.id_hpt
                    ");
                    while ($row = $stmt->fetch()) {
                        echo "<tr>
                            <td>{$row['id_pat']}</td>
                            <td>{$row['nom']}</td>
                            <td>{$row['prenom']}</td>
                            <td>{$row['hospital_name']}</td>
                            <td>{$row['numero']}</td>
                            <td>{$row['email']}</td>
                            <td>
                                <a href='edit_patient.php?id={$row['id_pat']}' class='btn btn-warning btn-sm'>Modifier</a>
                                <a href='delete_patient.php?id={$row['id_pat']}' class='btn btn-danger btn-sm'>Supprimer</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
