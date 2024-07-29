<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soutenance1";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête pour récupérer le patient basé sur l'email
    $sql = "SELECT id_pat, nom, password FROM patient WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Erreur de préparation de la requête : ' . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_pat, $nom, $stored_password);
        $stmt->fetch();

        if (password_verify($password, $stored_password)) {
            $_SESSION['user_id'] = $id_pat;
            $_SESSION['username'] = $nom;
            $_SESSION['role'] = 'patient';
            echo "Connexion réussie. <br>";

            header('Location: recherche.php');
            exit();
        } else {
            echo "Mot de passe incorrect. <br>";
        }
    } else {
        echo "Email non trouvé. <br>";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Patient</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Connexion Patient</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
            <?php
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger" role="alert">Identifiant ou mot de passe incorrect.</div>';
            }
            ?>
        </form>
    </div>
</body>
</html>
