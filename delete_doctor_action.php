<?php
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

$id = $_POST['id'];

$stmt = $pdo->prepare("DELETE FROM docteur WHERE id_doc = ?");
$stmt->execute([$id]);

header('Location: index.php');
