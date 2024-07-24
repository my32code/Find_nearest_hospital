<?php
session_start();

$default_username = 'citech';
$default_password = 'Soutenance1234';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $default_username && $password === $default_password) {
        $_SESSION['username'] = $username;
        header('Location: dashbord.php');
        exit();
    } else {
        header('Location: login.php?error=1');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>
