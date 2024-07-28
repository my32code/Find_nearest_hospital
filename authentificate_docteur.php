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

    // Requête pour récupérer le docteur basé sur l'email
    $sql = "SELECT id_doc, nom, password FROM docteur WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Erreur de préparation de la requête : ' . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_doc, $nom, $stored_password);
        $stmt->fetch();
        echo "Email trouvé. <br>";

        if ($password === $stored_password) {
            $_SESSION['user_id'] = $id_doc;
            $_SESSION['username'] = $nom;
            $_SESSION['role'] = 'docteur';
            echo "Mot de passe correct. Connexion réussie. <br>";
            
            header('Location: rendezvous_docteur.php');
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
