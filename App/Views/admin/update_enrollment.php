<?php
require_once '../../Database/Database.php';
session_start();

// Ensure only the admin can update
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die(json_encode(["error" => "Unauthorized access."]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->connect();

    $enrollment_id = $_POST['enrollment_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($enrollment_id && $action === 'accept') {
        $updateQuery = "UPDATE enrollments SET enrollment_status = 'approved' WHERE id = :enrollment_id";
        $stmt = $db->prepare($updateQuery);
        $stmt->bindParam(':enrollment_id', $enrollment_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../admin/enrolliest.php?success=approved");
            exit();
        } else {
            echo "<p class='text-red-500'>Error updating enrollment.</p>";
        }
    } else {
        echo "<p class='text-red-500'>Invalid request.</p>";
    }
} else {
    echo "<p class='text-red-500'>Invalid method.</p>";
}
?>
