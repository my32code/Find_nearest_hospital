<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soutenance1";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_ville = $_POST['new_ville'];
    $new_commune = $_POST['new_commune'];
    $new_arrondissement = $_POST['new_arrondissement'];

    if (empty($new_ville) || empty($new_commune) || empty($new_arrondissement)) {
        echo "Veuillez remplir tous les champs.";
        exit();
    }

    $sql = "INSERT INTO adress (ville, commune, arrondissement) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $new_ville, $new_commune, $new_arrondissement);
    $stmt->execute();

    header('Location: add_hospital.php');
    exit();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une nouvelle adresse</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            width: 600px;
        }
        .form-container h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #007bff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            width: 100%;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Ajouter une nouvelle adresse</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="new_ville">Nouvelle Ville</label>
                <input type="text" class="form-control" id="new_ville" name="new_ville">
            </div>
            <div class="form-group">
                <label for="new_commune">Nouvelle Commune</label>
                <input type="text" class="form-control" id="new_commune" name="new_commune">
            </div>
            <div class="form-group">
                <label for="new_arrondissement">Nouvel Arrondissement</label>
                <input type="text" class="form-control" id="new_arrondissement" name="new_arrondissement">
            </div>
            <button type="submit" class="btn btn-primary">Soumettre</button>
        </form>
    </div>
</body>
</html>
