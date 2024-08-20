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
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'docteur') {
    header('Location: login_docteur.php');
    exit();
}

$docteur_id = $_SESSION['user_id'];
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f7f9; /* Couleur de fond générale */
        }
        
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .container {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff; /* Fond de la table */
            border-radius: 5px;
            overflow: hidden;
        }
        table, th, td {
            border: 1px solid #ddd; /* Bordure de la table */
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff; /* Fond des en-têtes */
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9; /* Fond des lignes paires */
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        button {
            background-color: #007bff; /* Couleur du bouton */
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        input[type="datetime-local"] {
            padding: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    
    <div class="content">
        <h1>Mes Rendez-vous</h1>
        <table>
            <thead>
                <tr>
                    <th>Nom du Patient</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Date et Heure du Rendez-vous</th>
                    <th>Description</th>
                    <th>Actions</th>
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
                        <td class="action-buttons">
                            <form action="edit_rendezvous.php" method="POST" style="display:inline;">
                                <input type="hidden" name="rendezvous_id" value="<?php echo $row['id_rend']; ?>">
                                <button type="submit">Modifier</button>
                            </form>
                            <form action="delete_rendezvous.php" method="POST" style="display:inline;">
                                <input type="hidden" name="rendezvous_id" value="<?php echo $row['id_rend']; ?>">
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
