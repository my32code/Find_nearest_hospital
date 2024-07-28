<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soutenance1";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification des identifiants admin
    if ($email === 'admin@gmail.com' && $password === 'Soutenance1234') {
        $_SESSION['username'] = 'admin';
        header('Location: dashbord.php');
        exit();
    } else {
        // Vérification des identifiants docteur
        $sql = "SELECT id_doc, password FROM docteur WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Erreur de préparation de la requête : " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Vérifier si le mot de passe correspond
            if ($password === $row['password']) {
                $_SESSION['docteur_id'] = $row['id_doc'];
                header('Location: rendezvous_docteur.php');
                exit();
            } else {
                echo "Mot de passe incorrect"; // Message de débogage
                // header('Location: index.php?error=1');
                exit();
            }
        } else {
            echo "Email non trouvé"; // Message de débogage
            // header('Location: index.php?error=1');
            exit();
        }
    }
} else {
    header('Location: index.php');
    exit();
}

$conn->close();
?>
