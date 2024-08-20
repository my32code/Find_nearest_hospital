<?php
// success.php
session_start();

// Vérifiez que l'utilisateur est connecté en tant que patient
if (!isset($_SESSION['user_id'], $_SESSION['username']) || $_SESSION['role'] !== 'patient') {
    header('Location: login_patient.php');
    exit();
}

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soutenance1";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les informations de transaction
$transactionId = $_GET['transactionId'];
$userId = $_SESSION['user_id'];

// Enregistrer le rendez-vous dans la base de données (ajustez les champs et la table selon votre structure)
$sql = "INSERT INTO rendezvous (id_pat, id_doc, status, date/heure_rendezvous, description) VALUES (?, ?, 'confirmé', NOW(), 'Rendez-vous payé via KKPAY')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userId, $doctorId); // Assurez-vous que $doctorId est défini correctement
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Rendez-vous pris avec succès. Votre ID de transaction est " . htmlspecialchars($transactionId);
} else {
    echo "Erreur lors de la prise du rendez-vous.";
}

$stmt->close();
$conn->close();
?>
