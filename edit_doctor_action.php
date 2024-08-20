<?php
$pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');

// Récupérer les valeurs du formulaire
$id = $_POST['id'];
$doctorName = $_POST['doctorName'];
$doctorFirstName = $_POST['doctorFirstName'];
$sexe = $_POST['sexe'];
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

// Mettre à jour les informations du docteur sans modifier l'email et le mot de passe
$stmt = $pdo->prepare("UPDATE docteur SET nom = ?, prenom = ?, sexe = ?, id_hpt = ?, id_sp = ?, numero = ?, disponibility = ? WHERE id_doc = ?");
$stmt->execute([$doctorName, $doctorFirstName, $sexe, $hospital, $speciality, $phoneNumber, $disponibility_json, $id]);

header('Location: dashbord.php');
?>
