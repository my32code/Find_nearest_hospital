<?php
$pdo = new PDO('mysql:host=localhost;dbname=database_name;charset=utf8', 'username', 'password');

$id = $_POST['id'];

$stmt = $pdo->prepare("DELETE FROM hopital WHERE id_hpt = ?");
$stmt->execute([$id]);

header('Location: index.php');
