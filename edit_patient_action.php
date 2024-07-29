<?php
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

$id = $_POST['id'];
$patientName = $_POST['patientName'];
$patientFirstName = $_POST['patientFirstName'];
$sexe = $_POST['sexe'];
$phoneNumber = $_POST['phoneNumber'];
$email = $_POST['email'];

$stmt = $pdo->prepare("UPDATE patient SET nom = ?, prenom = ?, sexe = ?, numero = ?, email = ? WHERE id_pat = ?");
$stmt->execute([$patientName, $patientFirstName, $sexe, $phoneNumber, $email, $id]);

header('Location: dashbord.php');
