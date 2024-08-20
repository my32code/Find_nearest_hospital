<?php
    session_start();

    // Vérifiez que l'utilisateur est connecté en tant que patient
    if (!isset($_SESSION['user_id'], $_SESSION['username']) || $_SESSION['role'] !== 'patient') {
        header('Location: login_patient.php');
        exit();
    }

    if (!isset($_GET['doctorId']) || !isset($_GET['hopitalId'])) {
        echo "ID du docteur ou de l'hôpital non spécifié.";
        exit();
    }

    $doctorId = $_GET['doctorId'];
    $hopitalId = $_GET['hopitalId'];

    // Connexion à la base de données
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupération des informations du docteur
        $stmt = $pdo->prepare("SELECT id_doc, nom, prenom, disponibility FROM docteur WHERE id_doc = :doctorId");
        $stmt->execute(['doctorId' => $doctorId]);
        $docteur = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$docteur) {
            echo "Docteur non trouvé.";
            exit();
        }

        // Récupération des informations du patient
        $stmt = $pdo->prepare("SELECT nom, prenom, numero, email FROM patient WHERE id_pat = :userId");
        $stmt->execute(['userId' => $_SESSION['user_id']]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$patient) {
            echo "Patient non trouvé.";
            exit();
        }

        // Récupérer les informations du docteur
        $stmt = $pdo->prepare("SELECT nom, prenom, disponibility FROM docteur WHERE id_doc = :doctorId");
        $stmt->execute(['doctorId' => $doctorId]);
        $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Récupérer les disponibilités du docteur
        $disponibilities = json_decode($docteur['disponibility'], true);

        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        

        function parseDisponibilities($disponibilities) {
        $result = [];
        foreach ($disponibilities as $dispo) {
            $result[] = [
                'start' => $dispo['start_time'],
                'end' => $dispo['end_time']
            ];
        }
        return $result;
    }

    $parsedDisponibilities = parseDisponibilities($disponibilities);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.kkiapay.me/k.js">
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
    <title>Demander un rendez-vous</title>
</head>
<body>
    <h1>Demander un rendez-vous</h1>
    <form class="form-section" id="rendezvousForm" method="POST" action="paiement.php">
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
                <input type="datetime-local" id="date_heure_rendezvous" name="date_heure_rendezvous" required>
            </div>
        </div>
        <div class="form-line">
            <label class="form-label" for="description">Description</label>
            <div class="form-input">
                <textarea id="description" name="description" class="form-textbox" rows="4" placeholder="Description du rendez-vous" required></textarea>
            </div>
        </div>

        <input type="hidden" name="doctorId" value="<?= htmlspecialchars($doctorId) ?>">
        <input type="hidden" name="hopitalId" value="<?= htmlspecialchars($hopitalId) ?>">
        
        <div class="form-line">
        </div>
    </form>
    <body>    
            <form id="confirmation-form" action="paiement.php" method="post">        
                <input type="hidden" name="nom" value="<?php echo htmlspecialchars($nom); ?>">       
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">       
                <input type="hidden" name="disponibilite" value="<?php echo htmlspecialchars($disponibilite); ?>">
                <input type="hidden" name="cycle" value="<?php echo htmlspecialchars($cycle); ?>">
                <input type="hidden" name="classe" value="<?php echo htmlspecialchars($classe); ?>"> 
                <input type="hidden" name="matiere" value="<?php echo htmlspecialchars($matiere); ?>">        
                <input type="hidden" name="cv" value="<?php echo htmlspecialchars($cv); ?>">
                <input type="hidden" name="identite" value="<?php echo htmlspecialchars($identite); ?>">
                <input type="hidden" name="diplome" value="<?php echo htmlspecialchars($diplome); ?>">        
                <input type="hidden" name="montant" value="<?php echo htmlspecialchars($montant); ?>">
           </form>    
           <div class="container">        
           <h3>Vérifiez vos informations</h3>        
           <table class="confirmation-table">
            <tr>                
            <th>Nom</th>                
            <td><?php echo htmlspecialchars($nom); ?></td>
            </tr>            
            <tr>                
            <th>Email</th>                
            <td><?php echo htmlspecialchars($email); ?></td> 
            </tr>            <tr>
            <th>Disponibilité</th> 
            <td><?php echo htmlspecialchars($disponibilite); ?></td>          
            </tr>            <tr>                <th>Cycle</th>               
            <td><?php echo htmlspecialchars($cycle); ?></td>            
            </tr>            <tr>                <th>Classe</th>                
            <td><?php echo htmlspecialchars($classe); ?></td>            </tr>
            <tr>                <th>Matière</th>                
            <td><?php echo htmlspecialchars($matiere); ?></td> 

            </tr>            <tr>   
            <th>CV</th>                
            <td><a href="../allsociety/img/<?php echo htmlspecialchars($cv); ?>" target="_blank">Télécharger CV</a></td>           
            </tr>            <tr>                <th>Pièce d'identité</th>                
            <td><a href="../allsociety/img/<?php echo htmlspecialchars($identite); ?>" target="_blank">Télécharger Pièce d'identité</a></td> 
            </tr>            <tr>                <th>Diplôme</th>                
           <td><a href="../allsociety/img/<?php echo htmlspecialchars($diplome); ?>" target="_blank">Télécharger Diplôme</a></td>
            </tr>            <tr>                <th>Montant</th>               
            <td><?php echo htmlspecialchars($montant); ?> FCFA</td>
            </tr>        </table>
        <h3>Finalisez votre paiement</h3> 
            <div style="margin-left:300px;" id="kkiapay-container"></div>
    </div>
    <script src="https://cdn.kkiapay.me/k.js"></script>   
     <script>    document.addEventListener('DOMContentLoaded', function () {        var montant = <?php echo json_encode($montant); ?>;  
    var widgetContainer = document.getElementById('kkiapay-container');        var widget = document.createElement('kkiapay-widget');     
    widget.setAttribute('amount', montant);        widget.setAttribute('key', '1b397c4051d911efa51cd9ada78c8bb7');
             // Assurez-vous que la clé est correcte        widget.setAttribute('position', 'center');        widget.setAttribute('sandbox', 'true');        widget.setAttribute('data', '');        widgetContainer.appendChild(widget);
        // Fonction pour gérer la redirection après le paiement        var redirectAfterPayment = function() {            var form = document.getElementById('confirmation-form');            var formData = new FormData(form);
            // Envoyer les données du formulaire au même script PHP            fetch('paiement.php', {                method: 'POST',                body: formData            })            .then(response => response.json())            .then(data => {                if (data.status === 'success') {                    // Redirection vers l'URL spécifiée après paiement réussi                    window.location.href = data.redirect_url;                } else {                    alert('Erreur : ' + data.message);                }            })            .catch(error => {                console.error('Erreur lors de l\'envoi des données :', error);            });        };
        // Écouter l'événement de paiement réussi        widget.addEventListener('payment-success', function() {            redirectAfterPayment();        });    });    </script></body>

   
