<?php
$pdo = new PDO('mysql:host=localhost;dbname=database_name;charset=utf8', 'username', 'password');

$id = $_POST['id'];
$hospitalName = $_POST['hospitalName'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$address = $_POST['address'];
$phoneNumber = $_POST['phoneNumber'];

$stmt = $pdo->prepare("UPDATE hopital SET nom = ?, latitude = ?, longitude = ?, id_ad = ?, numero = ? WHERE id_hpt = ?");
$stmt->execute([$hospitalName, $latitude, $longitude, $address, $phoneNumber, $id]);

header('Location: index.php');
