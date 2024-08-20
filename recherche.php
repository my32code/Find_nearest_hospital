<?php
    session_start();

    if (!isset($_SESSION['user_id'], $_SESSION['username']) || $_SESSION['role'] !== 'patient') {
        header('Location: login_patient.php');
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "soutenance1";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "
    SELECT 
        hopital.id_hpt,
        hopital.nom,
        hopital.numero, 
        hopital.horaire, 
        hopital.latitude, 
        hopital.longitude,
        GROUP_CONCAT(DISTINCT speciality.libelle SEPARATOR ', ') AS specialites
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

    $filteredHospitals = $hospitals;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['speciality'])) {
        $searchTerm = strtolower($_POST['speciality']);
        $filteredHospitals = array_filter($hospitals, function($hospital) use ($searchTerm) {
            return strpos(strtolower($hospital['specialites']), $searchTerm) !== false;
        });
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
        <form id="searchForm" onsubmit="filterHospitals(event)">
            <input type="text" id="searchInput" placeholder="Rechercher une spécialité...">
            <input type="hidden" id="userLat">
            <input type="hidden" id="userLng">
            <input type="submit" value="Rechercher">
        </form>
        <div id="location"></div>
        <div id="hospitals"></div>
    </div>

    <script>
        const hospitals = <?php echo json_encode($hospitals); ?>;

        function showDoctors(hopitalId) {
            window.location.href = `voirdoc.php?hopitalId=${hopitalId}`;
        }

        function calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371; // Rayon de la Terre en kilomètres
            const dLat = deg2rad(lat2 - lat1);
            const dLng = deg2rad(lng2 - lng1);
            const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                    Math.sin(dLng/2) * Math.sin(dLng/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            return R * c;
        }

        function deg2rad(deg) {
            return deg * (Math.PI/180);
        }

        function displayHospitals(hospitals) {
            let hospitalsDiv = document.getElementById("hospitals");
            hospitalsDiv.innerHTML = '<h2>Hôpitaux les plus proches</h2>';
            hospitals.forEach(hospital => {
                const link = `https://www.google.com/maps/search/?api=1&query=${hospital.latitude},${hospital.longitude}`;        
                hospitalsDiv.innerHTML += 
                `<div>
                    <p><a href="${link}" target="_blank">${hospital.nom}</a><br>
                    Numéro: ${hospital.numero}<br>
                    Horaires: ${hospital.horaire}<br>
                    Spécialités: ${hospital.specialites}<br>
                    Distance: ${hospital.distance.toFixed(2)} km</p>
                    <button onclick="showDoctors(${hospital.id_hpt})">Demandez un rendez-vous</button>
                </div>`;    
            });
        }

        function filterHospitals(event) {
            event.preventDefault();
            const input = document.getElementById('searchInput').value.toLowerCase();
            const userLat = parseFloat(document.getElementById('userLat').value);
            const userLng = parseFloat(document.getElementById('userLng').value);
            
            let nearestHospitals = [];

            hospitals.forEach(hospital => {
                if (hospital.specialites.toLowerCase().includes(input)) {
                    const distance = calculateDistance(userLat, userLng, hospital.latitude, hospital.longitude);
                    nearestHospitals.push({ 
                        ...hospital, 
                        distance 
                    });
                }
            });

            nearestHospitals.sort((a, b) => a.distance - b.distance);

            displayHospitals(nearestHospitals);
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                document.getElementById("location").innerHTML = "La géolocalisation n'est pas supportée par ce navigateur.";
            }
        }

        function showPosition(position) {
            document.getElementById('userLat').value = position.coords.latitude;
            document.getElementById('userLng').value = position.coords.longitude;

            filterHospitals(new Event('submit'));
        }

        function showError(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    document.getElementById("location").innerHTML = "L'utilisateur a refusé la demande de géolocalisation.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    document.getElementById("location").innerHTML = "Les informations de localisation ne sont pas disponibles.";
                    break;
                case error.TIMEOUT:
                    document.getElementById("location").innerHTML = "La demande de localisation a expiré.";
                    break;
                case error.UNKNOWN_ERROR:
                    document.getElementById("location").innerHTML = "Une erreur inconnue est survenue.";
                    break;
            }
        }

        getLocation();
    </script>
</body>
</html>
