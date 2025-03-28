<?php
require_once __DIR__  . '/../Database/Database.php';
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("<p class='text-red-500'>Access Denied. Admins only.</p>");
}

if (!isset($_POST['enrollment_id'], $_POST['action'])) {
    die("Invalid request.");
}

$enrollment_id = $_POST['enrollment_id'];
$action = $_POST['action'];

$database = new Database();
$db = $database->connect();

try {
    // Start a transaction so that both the enrollment and user updates occur together
    $db->beginTransaction();

    // Update the enrollment status based on the action
    if ($action === 'accept') {
        $updateEnrollmentQuery = "UPDATE enrollments SET enrollment_status = 'approved' WHERE id = :enrollment_id";
    } elseif ($action === 'reject') {
        $updateEnrollmentQuery = "UPDATE enrollments SET enrollment_status = 'rejected' WHERE id = :enrollment_id";
    } else {
        throw new Exception("Invalid action.");
    }

    $stmt = $db->prepare($updateEnrollmentQuery);
    $stmt->bindParam(':enrollment_id', $enrollment_id, PDO::PARAM_INT);
    $stmt->execute();

    // Only update the user's grade/semester if the enrollment was accepted
    if ($action === 'accept') {
        // Retrieve the user's current grade and semester by joining enrollments and users tables
        $userQuery = "SELECT u.id, u.grade, u.semester
                      FROM enrollments e
                      JOIN users u ON e.user_id = u.id
                      WHERE e.id = :enrollment_id";
        $stmt = $db->prepare($userQuery);
        $stmt->bindParam(':enrollment_id', $enrollment_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $user_id = $user['id'];
            $current_grade = $user['grade'];
            // Normalize the semester string to lowercase to avoid case issues
            $current_semester = strtolower(trim($user['semester']));

            $new_grade = $current_grade;
            $new_semester = $current_semester;

            // Apply the update rules:
            if ($current_grade == 11 && strpos($current_semester, '1st semester') !== false) {
                // Rule 1: Grade 11, 1st semester → update semester to 2nd semester
                $new_semester = "2nd semester";
            } elseif ($current_grade == 11 && strpos($current_semester, '2nd semester') !== false) {
                // Rule 2: Grade 11, 2nd semester → update grade to 12 and semester to 1st semester
                $new_grade = 12;
                $new_semester = "1st semester";
            } elseif ($current_grade == 12 && strpos($current_semester, '1st semester') !== false) {
                // Rule 3: Grade 12, 1st semester → update semester to 2nd semester
                $new_semester = "2nd semester";
            }
            // If an update is needed (i.e. changes exist), update the user's record
            if ($new_grade != $current_grade || $new_semester != $current_semester) {
                $updateUserQuery = "UPDATE users SET grade = :new_grade, semester = :new_semester WHERE id = :user_id";
                $stmt = $db->prepare($updateUserQuery);
                $stmt->bindParam(':new_grade', $new_grade, PDO::PARAM_INT);
                $stmt->bindParam(':new_semester', $new_semester);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
    }

    // Commit all changes
    $db->commit();
    
    // Redirect back to the enrollment list
    header("Location: ../../../Views/admin/enrolliest.php");
    exit;
} catch (Exception $e) {
    // Roll back the transaction on error
    $db->rollBack();
    die("Error: " . $e->getMessage());
}
?>
