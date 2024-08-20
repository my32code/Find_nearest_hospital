<?php
// initiate_payment.php
header('Content-Type: application/json');

// Récupérer les données de la requête
$data = json_decode(file_get_contents('php://input'), true);
$amount = $data['amount'];
$paymentMethod = $data['paymentMethod'];

// Simuler l'appel à l'API KKPAY
$transactionId = uniqid(); // Simuler un ID de transaction unique

// Simuler une réponse de succès de KKPAY
$response = [
    'success' => true,
    'transactionId' => $transactionId
];

echo json_encode($response);
?>
