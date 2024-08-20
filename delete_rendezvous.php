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

// Vérifiez que le docteur est connecté
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'docteur') {
    header('Location: login_docteur.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rendezvous_id = $_POST['rendezvous_id'];

    $sql = "DELETE FROM rendezvous WHERE id_rend = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rendezvous_id);

    if ($stmt->execute()) {
        echo "Rendez-vous supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du rendez-vous : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header('Location: rendezvous_docteur.php'); // Redirigez vers la liste des rendez-vous après la suppression
    exit();
}
?>
