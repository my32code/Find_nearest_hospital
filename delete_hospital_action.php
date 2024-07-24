<?php
$pdo =new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

$id = $_POST['id'];

$stmt = $pdo->prepare("DELETE FROM hopital WHERE id_hpt = ?");
$stmt->execute([$id]);

header('Location: dashbord.php');
