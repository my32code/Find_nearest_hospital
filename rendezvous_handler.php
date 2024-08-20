
<?php
session_start();

// Vérifiez que l'utilisateur est connecté en tant que patient
if (!isset($_SESSION['user_id'], $_SESSION['username']) || $_SESSION['role'] !== 'patient') {
    header('Location: login_patient.php');
    exit();
}

if (!isset($_POST['doctorId']) || !isset($_POST['hopitalId'])) {
    echo "ID du docteur ou de l'hôpital non spécifié.";
    exit();
}

$doctorId = $_POST['doctorId'];
$hopitalId = $_POST['hopitalId'];
$id_pat = $_SESSION['user_id']; // Utiliser la valeur réelle de l'utilisateur connecté

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifiez que l'utilisateur existe dans la table patient
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM patient WHERE id_pat = ?");
    $stmt_check->execute([$id_pat]);
    $user_exists = $stmt_check->fetchColumn();

    if ($user_exists == 0) {
        echo "L'utilisateur n'existe pas dans la table patient.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date_heure_rend = $_POST['date_heure_rendezvous'];
        $description = $_POST['description'];
        $tab = [$id_pat, $doctorId, $date_heure_rend, $description];
        
        $stmt = $pdo->prepare("INSERT INTO rendezvous (id_pat, id_doc, date_heure_rendezvous, description) VALUES (?, ?, ?, ?)");
        $stmt->execute($tab);
        
        echo "Rendez-vous pris avec succès.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

