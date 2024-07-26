<?php
session_start();

// Identifiants admin
$default_username_admin = 'citech';
$default_password_admin = 'Soutenance1234';
// Identifiants docteur
$default_username_doctor = 'citech';
$default_password_doctor = 'Soutenance567';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérification des identifiants admin
    if ($username === $default_username_admin && $password === $default_password_admin) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin'; // Ajoutez un rôle si nécessaire
        header('Location: dashbord.php');
        exit();
    }
    // Vérification des identifiants docteur
    elseif ($username === $default_username_doctor && $password === $default_password_doctor) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'docteur'; // Ajoutez un rôle si nécessaire
        header('Location: rendezvous_docteur.php');
        exit();
    } 
    else {
        header('Location: index.php?error=1');
        exit();
    }
} 
else {
    header('Location: index.php');
    exit();
}
?>
