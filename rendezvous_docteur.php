<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "soutenance1";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Vérifiez que le docteur est connecté
if (!isset($_SESSION['docteur_id'])) {
    header('Location: login.php');
    exit();
}

$docteur_id = $_SESSION['docteur_id'];
$sql = "SELECT rendezvous.*, patient.nom AS patient_nom, patient.prenom AS patient_prenom, patient.numero, patient.email 
        FROM rendezvous 
        JOIN patient ON rendezvous.id_pat = patient.id_pat 
        WHERE rendezvous.id_doc = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $docteur_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rendez-vous des Patients</title>
    <style>
        body {
            font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, sans-serif;
            color: #555;
            max-width: 800px;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Rendez-vous des Patients</h1>
    <table>
        <thead>
            <tr>
                <th>Nom du Patient</th>
                <th>Téléphone</th>
                <th>Email</th>
                <th>Date et Heure du Rendez-vous</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['patient_prenom'] . ' ' . $row['patient_nom']); ?></td>
                    <td><?php echo htmlspecialchars($row['numero']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_heure_rendezvous']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['status'] ? 'Actif' : 'Inactif'); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
