<?php
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

$doctorName = $_POST['doctorName'];
$doctorFirstName = $_POST['doctorFirstName'];
$hospital = $_POST['hospital'];
$speciality = $_POST['speciality'];
$phoneNumber = $_POST['phoneNumber'];
$disponibility = $_POST['disponibility'];

$stmt = $pdo->prepare("INSERT INTO docteur (nom, prenom, id_hpt, id_sp, numero,disponibility) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$doctorName, $doctorFirstName, $hospital, $speciality, $phoneNumber, $disponibility]);

header('Location: dashbord.php');
