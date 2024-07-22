<?php
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

$id = $_POST['id'];
$doctorName = $_POST['doctorName'];
$doctorFirstName = $_POST['doctorFirstName'];
$hospital = $_POST['hospital'];
$speciality = $_POST['speciality'];
$phoneNumber = $_POST['phoneNumber'];

$stmt = $pdo->prepare("UPDATE docteur SET nom = ?, prenom = ?, id_hpt = ?, id_sp = ?, numero = ? WHERE id_doc = ?");
$stmt->execute([$doctorName, $doctorFirstName, $hospital, $speciality, $phoneNumber, $id]);

header('Location: index.php');
