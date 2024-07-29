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

    // Remplacez ces valeurs par les véritables identifiants de l'admin
    $admin_email = "admin@gmail.com";
    $admin_password = "Soutenance1234";

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['username'] = "Admin";
        $_SESSION['role'] = 'admin';
        echo "Connexion réussie. <br>";

        header('Location: dashbord.php');
        exit();
    } else {
        echo "Identifiant ou mot de passe incorrect. <br>";
    }
}

$conn->close();
?>
