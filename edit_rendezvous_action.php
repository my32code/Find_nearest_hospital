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

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'docteur') {
    header('Location: login_docteur.php');
    exit();
}

$rendezvous_id = $_POST['rendezvous_id'];
$date_heure_rendezvous = $_POST['date_heure_rendezvous'];

$sql = "UPDATE rendezvous SET date_heure_rendezvous = ? WHERE id_rend = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $date_heure_rendezvous, $rendezvous_id);

if ($stmt->execute()) {
    header('Location: rendezvous_docteur.php');
} else {
    echo "Erreur : " . $stmt->error;
}

$stmt->close();
$conn->close();
?>