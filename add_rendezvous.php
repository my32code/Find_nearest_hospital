<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soutenance1";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Insérer des données dans la table rendezvous
$sql = "INSERT INTO rendezvous (id_pat, id_doc, status, date_heure_rendezvous, description) 
        VALUES (1, 1, 1, '2024-07-25 14:30:00', 'Consultation initiale')";

if ($conn->query($sql) === TRUE) {
    echo "Nouveau rendez-vous ajouté avec succès";
} else {
    echo "Erreur : " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
