<?php
session_start();

// Vérifiez que l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

// Récupération du nombre d'hôpitaux
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM hopital");
$hospitalCount = $stmt->fetch()['count'];

// Récupération du nombre de docteurs
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM docteur");
$doctorCount = $stmt->fetch()['count'];

// Récupération du nombre de patients
$stmt = $pdo->query("SELECT COUNT(*) AS count FROM patient");
$patientCount = $stmt->fetch()['count'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .sidebar {
            width: 200px; /* Réduction de la taille de la barre latérale */
            background-color: #007bff;
            color: #fff;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px; /* Réduction de la taille de padding */
            width: 100%;
            text-decoration: none;
            color: #fff;
            transition: background-color 0.3s, transform 0.3s;
            margin: 5px 0; /* Réduction de l'espacement entre les éléments */
            flex-direction: column; /* Alignement des icônes et du texte */
        }
        .sidebar a:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }
        .sidebar a i {
            margin-right: 10px;
            font-size: 1.5em;
        }
        .sidebar .hopital-icon {
            font-size: 5em; /* Augmentation de la taille de l'icône de l'utilisateur */
            margin-bottom: 20px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f8f9fa;
            overflow-y: auto;
        }
        .container {
            margin-top: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-icon {
            font-size: 4em;
            margin-right: 15px;
        }
        .card-title {
            font-size: 1.5em;
            font-weight: bold;
        }
        .stat-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
        }
        .stat-card h3 {
            margin-top: 0;
            font-size: 2em;
        }
        .chart-container {
            margin-top: 30px;
        }
        .chart-wrapper {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <span><i class="fas fa-hospital hopital-icon"></i></span>
        <a href="dashbord.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="hopitaux.php"><i class="fas fa-h-square"></i> Gestion des Hôpitaux</a>
        <a href="doctors.php"><i class="fas fa-user-md"></i> Docteurs</a>
        <a href="patients.php"><i class="fas fa-procedures"></i> Patients</a>
        <a href="index.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
    </div>
    <div class="content">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <p>Vous êtes connecté en tant qu'admin.</p>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-hospital card-icon"></i>
                            <div>
                                <div class="card-title">Hôpitaux</div>
                                <div class="card-text"><?php echo $hospitalCount; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-success">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-user-md card-icon"></i>
                            <div>
                                <div class="card-title">Docteurs</div>
                                <div class="card-text"><?php echo $doctorCount; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body d-flex align-items-center">
                            <i class="fas fa-procedures card-icon"></i>
                            <div>
                                <div class="card-title">Patients</div>
                                <div class="card-text"><?php echo $patientCount; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="chart-container">
            <div class="chart-wrapper">
                <h3>Nombre de Docteurs au fil du temps</h3>
                <canvas id="doctorsChart"></canvas>
            </div>
            <div class="chart-wrapper">
                <h3>Nombre de Patients au fil du temps</h3>
                <canvas id="patientsChart"></canvas>
            </div>
        </div>
        </div>
    </div>
    <script>
        // Remplacez ces données par des données réelles issues de votre base de données
        const doctorData = {
            labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août'],
            datasets: [{
                label: 'Docteurs',
                data: [10, 15, 12, 14, 18, 20, 22, 25],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true,
                tension: 0.4
            }]
        };

        const patientData = {
            labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août'],
            datasets: [{
                label: 'Patients',
                data: [50, 55, 60, 65, 70, 75, 80, 90],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                fill: true,
                tension: 0.4
            }]
        };

        const config = {
            type: 'line',
            data: doctorData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const config2 = {
            type: 'line',
            data: patientData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const doctorsChart = new Chart(
            document.getElementById('doctorsChart'),
            config
        );

        const patientsChart = new Chart(
            document.getElementById('patientsChart'),
            config2
        );
    </script>
</body>
</html>
