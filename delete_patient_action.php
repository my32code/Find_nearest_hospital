<?php
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

$id = $_POST['id'];

$stmt = $pdo->prepare("DELETE FROM patient WHERE id_pat = ?");
$stmt->execute([$id]);

header('Location: index.php');
