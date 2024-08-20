<?php
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

// Récupérer les valeurs du formulaire
$doctorName = $_POST['doctorName'];
$doctorFirstName = $_POST['doctorFirstName'];
$hospital = $_POST['hospital'];
$speciality = $_POST['speciality'];
$phoneNumber = $_POST['phoneNumber'];

// Récupérer les plages horaires et les convertir en JSON
$start_times = $_POST['start_time'];
$end_times = $_POST['end_time'];
$disponibilities = [];

for ($i = 0; $i < count($start_times); $i++) {
    $disponibilities[] = [
        'start_time' => $start_times[$i],
        'end_time' => $end_times[$i]
    ];
}

$disponibility_json = json_encode($disponibilities);

// Récupérer le nombre total de docteurs pour générer l'email et le mot de passe uniques
$stmt = $pdo->query("SELECT COUNT(*) AS total_doctors FROM docteur");
$total_doctors = $stmt->fetch(PDO::FETCH_ASSOC)['total_doctors'];
$new_id = $total_doctors + 1;

$email = "doc{$new_id}@gmail.com";
$password = "doc{$new_id}@gmail.com";

// Insérer le nouveau docteur dans la base de données
$stmt = $pdo->prepare("INSERT INTO docteur (nom, prenom, id_hpt, id_sp, numero, disponibility, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$doctorName, $doctorFirstName, $hospital, $speciality, $phoneNumber, $disponibility_json, $email, $password]);

header('Location: dashbord.php');
?>
