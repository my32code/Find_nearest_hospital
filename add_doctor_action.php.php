<?php
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

$doctorName = $_POST['doctorName'];
$doctorFirstName = $_POST['doctorFirstName'];
$hospital = $_POST['hospital'];
$speciality = $_POST['speciality'];
$phoneNumber = $_POST['phoneNumber'];

$stmt = $pdo->prepare("INSERT INTO docteur (nom, prenom, id_hpt, id_sp, numero) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$doctorName, $doctorFirstName, $hospital, $speciality, $phoneNumber]);

header('Location: index.php');
