<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
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
    <div class="content">
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