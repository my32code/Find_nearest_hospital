<?php
session_start();

// Vérifiez que l'utilisateur est connecté en tant que patient
if (!isset($_SESSION['user_id'], $_SESSION['username']) || $_SESSION['role'] !== 'patient') {
    header('Location: login_patient.php');
    exit();
}

if (!isset($_GET['hopitalId'])) {
    echo "ID de l'hôpital non spécifié.";
    exit();
}

$hopitalId = $_GET['hopitalId'];

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des docteurs pour l'hôpital spécifié
    $stmt = $pdo->prepare("SELECT id_doc, nom, prenom, disponibility FROM docteur WHERE id_hpt = :hopitalId");
    $stmt->execute(['hopitalId' => $hopitalId]);
    $docteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Fonction pour extraire les jours et heures de disponibilité
function getAvailableSchedules($docteurs) {
    $schedules = [];
    foreach ($docteurs as $docteur) {
        $dispos = json_decode($docteur['disponibility'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($dispos as $dispo) {
                // Vérifiez que les clés 'start_time' et 'end_time' existent
                if (isset($dispo['start_time']) && isset($dispo['end_time'])) {
                    $startDay = date('l', strtotime($dispo['start_time'])); // Format : Monday, Tuesday, etc.
                    $startTime = date('H:i', strtotime($dispo['start_time'])); // Heure de début
                    $endTime = date('H:i', strtotime($dispo['end_time'])); // Heure de fin

                    // Ajoutez la disponibilité au tableau si elle n'existe pas déjà
                    if (!isset($schedules[$startDay])) {
                        $schedules[$startDay] = [];
                    }
                    $schedules[$startDay][] = "$startTime - $endTime";
                }
            }
        } else {
            // Affichez une erreur si le JSON est mal formé
            echo "Erreur de décodage JSON : " . json_last_error_msg();
        }
    }
    return $schedules;
}
// Configurez la locale en français
// setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'fr');
$availableSchedules = getAvailableSchedules($docteurs);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Docteurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #444;
        }
        .doctor-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin: 20px 0;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .doctor-card p {
            margin: 10px 0;
        }
        .doctor-card button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .doctor-card button:hover {
            background-color: #0056b3;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const disponibilites = <?= json_encode($docteurs) ?>;

            flatpickr("#date_heure_rendezvous", {
                enableTime: true,
                dateFormat: "Y-m-d\\TH:i",
                enable: [
                    function(date) {
                        for (const dispo of disponibilites) {
                            const start = new Date(dispo.start_time);
                            const end = new Date(dispo.end_time);
                            if (date >= start && date <= end) {
                                return true;
                            }
                        }
                        return false;
                    }
                ]
            });
        });
    </script>
</head>
<body>
    <h1>Docteurs disponibles</h1>
    <?php foreach ($docteurs as $docteur): ?>
        <div class="doctor-card">
            <p>Docteur <?= htmlspecialchars($docteur['nom']) ?> <?= htmlspecialchars($docteur['prenom']) ?></p>
            <p>Disponibilité:
                <ul>
                    <?php foreach ($availableSchedules as $day => $times): ?>
                        <li>
                            <?= htmlspecialchars($day) ?>: <?= implode(', ', $times) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </p>
            <button onclick="prendreRendezVousAvecDocteur(<?= $docteur['id_doc'] ?>, <?= $hopitalId ?>)">Prendre rendez-vous</button>
        </div>
    <?php endforeach; ?>

    <script>
        // Fonction pour rediriger vers la page de prise de rendez-vous avec l'ID du docteur et de l'hôpital
        function prendreRendezVousAvecDocteur(doctorId, hopitalId) {
            console.log("Redirection vers rendezvous.php avec l'ID du docteur:", doctorId, "et l'ID de l'hôpital:", hopitalId);
            window.location.href = `rendezvous.php?doctorId=${doctorId}&hopitalId=${hopitalId}`;
        }
    </script>
</body>
</html>
