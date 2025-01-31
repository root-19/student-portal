<?php
require_once __DIR__ . '/../Database/Database.php';
require_once  __DIR__ .'/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '../../');
$dotenv->load();

session_start();

if (!isset($_GET['user_id']) || !isset($_GET['fee'])) {
    die("<p class='text-red-500'>Invalid request. Missing parameters.</p>");
}

$user_id = (int) $_GET['user_id'];
$fee = (float) $_GET['fee'];

if ($fee <= 0) {
    die("<p class='text-red-500'>Invalid fee amount.</p>");
}

// PayMongo API Configuration
$paymongo_api_key = $_ENV['PAYMONGO_API_KEY']; 

$api_url = 'https://api.paymongo.com/v1/links';
$data = [
    'data' => [
        'attributes' => [
            'amount' => $fee * 100, //conert to cent
            'currency' => 'PHP',
            'description' => "Enrollment Fee for User ID $user_id",
            'redirect' => [
                'success' => 'http://localhost:8080/Views/user/index.php?status=success',
                'failed' => "http://localhost:8080/Views/user/index.php",
            ],
        ],
    ],
];

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($paymongo_api_key . ':'),
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    die("<p class='text-red-500'>cURL Error: " . curl_error($ch) . "</p>");
}

curl_close($ch);

$response_data = json_decode($response, true);

// ✅ FIXED: Correct key for checkout URL
if (!$response_data || !isset($response_data['data']['attributes']['checkout_url'])) {
    echo "<p class='text-red-500'>Failed to create payment. PayMongo response:</p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>"; // Debugging: Print response for analysis
    exit;
}

// ✅ Get the correct payment link
$payment_url = $response_data['data']['attributes']['checkout_url'];

// Log payment intent in the database
try {
    $database = new Database();
    $db = $database->connect();

    $query = "INSERT INTO payments (user_id, amount, status) VALUES (:user_id, :amount, 'pending')";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':amount', $fee);
    $stmt->execute();
} catch (Exception $e) {
    die("<p class='text-red-500'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>");
}

// ✅ Redirect to PayMongo checkout
header("Location: $payment_url");
exit;
?>