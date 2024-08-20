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

$rendezvous_id = $_POST['rendezvous_id'];
$sql = "SELECT * FROM rendezvous WHERE id_rend = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Erreur lors de la préparation de la requête : " . $conn->error);
}

$stmt->bind_param("i", $rendezvous_id);
$stmt->execute();
$result = $stmt->get_result();
$rendezvous = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Rendez-vous</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Modifier le Rendez-vous</h2>
        <form action="edit_rendezvous_action.php" method="post">
            <input type="hidden" name="rendezvous_id" value="<?php echo htmlspecialchars($rendezvous['id_rend']); ?>">
            <div class="form-group">
                <label for="date_heure_rendezvous">Date et Heure du Rendez-vous</label>
                <input type="datetime-local" class="form-control" id="date_heure_rendezvous" name="date_heure_rendezvous" value="<?php echo htmlspecialchars(str_replace(' ', 'T', $rendezvous['date_heure_rendezvous'])); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="docteur_dashboard.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
    <script>
        window.onerror = function(message, source, lineno, colno, error) {
            console.error(`Erreur: ${message} à ${source}:${lineno}:${colno}`, error);
        };
    </script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
