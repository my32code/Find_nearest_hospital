<?php
    session_start();

    // Vérifiez que l'utilisateur est connecté en tant que patient
    if (!isset($_SESSION['user_id'],$_SESSION['username']) || $_SESSION['role'] !== 'patient') {
        header('Location: login_patient.php');
        exit();
    }

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "soutenance1";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Récupération des données des hôpitaux avec les spécialités
    $sql = "
    SELECT 
        hopital.id_hpt,
        hopital.nom,
        hopital.numero, 
        hopital.horaire, 
        hopital.latitude, 
        hopital.longitude,
        GROUP_CONCAT(DISTINCT speciality.libelle SEPARATOR ', ') AS specialites,
        GROUP_CONCAT(DISTINCT docteur.disponibility SEPARATOR ', ') AS disponibilites
    FROM 
        hopital
    JOIN 
        docteur ON hopital.id_hpt = docteur.id_hpt
    JOIN 
        speciality ON docteur.id_sp = speciality.id_sp
    GROUP BY 
        hopital.id_hpt
";

    $result = $conn->query($sql);

    if (!$result) {
        die("Erreur dans la requête SQL : " . $conn->error);
    }

    $hospitals = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $hospitals[] = $row;
        }
    }

    $conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hôpitaux Proches</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Trouver l'Hôpital le Plus Proche</h1>
        <input type="text" id="searchInput" placeholder="Rechercher une spécialité..." onkeyup="filterHospitals()">
        <div id="location"></div>
        <div id="hospitals"></div>
        <!-- <button onclick="getLocation()">Mettre à jour la localisation</button> -->
    </div>
    
    
    <script>
        // Liste des hôpitaux avec leurs coordonnées GPS et informations supplémentaires
        const hospitals = <?php echo json_encode($hospitals); ?>;
    </script>
    <script src="script.js"></script>
</body>
</html>
