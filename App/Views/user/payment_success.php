<?php
session_start();

if (!isset($_SESSION['payment_url'])) {
    die("<p class='text-red-500'>No payment session found.</p>");
}

// Retrieve the payment URL from the session
$payment_url = $_SESSION['payment_url'];

// Clear the session value after retrieving
unset($_SESSION['payment_url']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin-top: 50px; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<h2>✅ Enrollment Request Submitted Successfully!</h2>
<p>Your payment link has been generated. Click the button below to proceed to PayMongo.</p>

<a href="<?= htmlspecialchars($payment_url) ?>" class="btn">Proceed to Payment</a>

</body>
</html>
