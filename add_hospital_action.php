<?php
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

$hospitalName = $_POST['hospitalName'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$address = $_POST['address'];
$phoneNumber = $_POST['phoneNumber'];

$stmt = $pdo->prepare("INSERT INTO hopital (nom, latitude, longitude, id_ad, numero) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$hospitalName, $latitude, $longitude, $address, $phoneNumber]);

header('Location: index.php');
