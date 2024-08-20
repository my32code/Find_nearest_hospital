<?php
session_start();

// Vérifiez que l'utilisateur est connecté en tant que patient
if (!isset($_SESSION['user_id'], $_SESSION['username']) || $_SESSION['role'] !== 'patient') {
    error_log("User not logged in or not a patient.");
    header('Location: login_patient.php');
    exit();
}

if (!isset($_POST['doctorId']) || !isset($_POST['hopitalId']) || !isset($_POST['date_heure_rendezvous']) || !isset($_POST['description'])) {
    error_log("Doctor ID, hospital ID, date or description not specified.");
    echo "ID du docteur, ID de l'hôpital, date ou description non spécifiés.";
    exit();
}

$doctorId = $_POST['doctorId'];
$hopitalId = $_POST['hopitalId'];
$dateHeureRendezvous = $_POST['date_heure_rendezvous'];
$description = $_POST['description'];
error_log("Doctor ID: $doctorId, Hospital ID: $hopitalId, Date/Heure: $dateHeureRendezvous, Description: $description");

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des informations du docteur
    $stmt = $pdo->prepare("SELECT id_doc, nom, prenom, disponibility FROM docteur WHERE id_doc = :doctorId");
    $stmt->execute(['doctorId' => $doctorId]);
    $docteur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$docteur) {
        error_log("Doctor not found.");
        echo "Docteur non trouvé.";
        exit();
    }

    // Récupération des informations du patient
    $stmt = $pdo->prepare("SELECT nom, prenom, numero, email FROM patient WHERE id_pat = :userId");
    $stmt->execute(['userId' => $_SESSION['user_id']]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$patient) {
        error_log("Patient not found.");
        echo "Patient non trouvé.";
        exit();
    }

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo "Erreur : " . $e->getMessage();
}

$montant = 2000;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        body {
            font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, sans-serif;
            color: #555;
            max-width: 600px;
            margin: auto;
            background-color: #f4f4f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #444;
        }
        .form-section {
            margin: 0;
            font-size: 14px;
        }
        .form-line {
            margin-bottom: 20px;
            position: relative;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-input {
            position: relative;
        }
        .form-input input, .form-input textarea, .form-input select {
            width: calc(100% - 10px);
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-input-wide {
            width: 100%;
        }
        .form-header-group {
            margin-bottom: 30px;
        }
        .header-text {
            font-size: 24px;
            font-weight: bold;
        }
        .form-submit {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-submit:hover {
            background-color: #0056b3;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-content h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .modal-content form {
            display: flex;
            flex-direction: column;
        }
        .modal-content form label {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .modal-content form input, 
        .modal-content form select, 
        .modal-content form button {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .modal-content form button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .modal-content form button:hover {
            background-color: #0056b3;
        }
        .modal-content #paymentMessage {
            text-align: center;
            font-size: 16px;
            color: green;
        }
    </style>
    <title>Paiement</title>
</head>
<body>
<h1>Confirmez vos informations</h1>
    <form class="form-section" id="rendezvousForm" method="POST" action="rendezvous_handler.php">
        <div class="form-line">
            <label class="form-label" for="nom_pat">Nom</label>
            <div class="form-input">
                <input type="text" id="nom_pat" value="<?= htmlspecialchars($patient['nom']) ?>" readonly required>
            </div>
        </div>
        <div class="form-line">
            <label class="form-label" for="prenom_pat">Prénom</label>
            <div class="form-input">
                <input type="text" id="prenom_pat" value="<?= htmlspecialchars($patient['prenom']) ?>" readonly required>
            </div>
        </div>
        <div class="form-line">
            <label class="form-label" for="numero_pat">Numéro</label>
            <div class="form-input">
                <input type="text" id="numero_pat" value="<?= htmlspecialchars($patient['numero']) ?>" readonly required>
            </div>
        </div>
        <div class="form-line">
            <label class="form-label" for="email_pat">Email</label>
            <div class="form-input">
                <input type="email" id="email_pat" value="<?= htmlspecialchars($patient['email']) ?>" readonly required>
            </div>
        </div>
        
        <div class="form-line">
            <label class="form-label" for="date_heure_rendezvous">Date et Heure du Rendez-vous</label>
            <div class="form-input">
                <input type="datetime-local" id="date_heure_rendezvous" name="date_heure_rendezvous" value="<?= htmlspecialchars($dateHeureRendezvous) ?>" required>
            </div>
        </div>
        <div class="form-line">
            <label class="form-label" for="description">Description</label>
            <div class="form-input">
                <textarea id="description" name="description" class="form-textbox" rows="4" required><?= htmlspecialchars($description) ?></textarea>
            </div>
        </div>
        <div class="form-line">
            <label class="form-label" for="montant">Montant</label>
            <div class="form-input">
                <input type="number" id="montant" name="montant" class="form-textbox" value="<?= $montant; ?>" readonly required>
            </div>
        </div>
        
        <div style="margin-left:300px;" id="kkiapay-container"></div>
        <script src="https://cdn.kkiapay.me/k.js"></script>
        <script>    
            document.addEventListener('DOMContentLoaded', function () {        
                var montant = <?= json_encode($montant); ?>;  
                var widgetContainer = document.getElementById('kkiapay-container');        
                var widget = document.createElement('kkiapay-widget');     
                widget.setAttribute('amount', montant);        
                widget.setAttribute('key', '4b6b817058e011ef93c41158b337c382');
                widget.setAttribute('sandbox', 'true');  // Activer le mode Sandbox
                widgetContainer.appendChild(widget); // Ajout du widget dans le DOM
            });
        </script>

        <input type="hidden" name="doctorId" value="<?= htmlspecialchars($doctorId) ?>">
        <input type="hidden" name="hopitalId" value="<?= htmlspecialchars($hopitalId) ?>">
        
        <div class="form-line">
            <button type="submit" id="submitBtn" class="form-submit">Valider</button>
        </div>
    </form>