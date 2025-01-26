<?php
session_start();
require_once __DIR__ . '/../Database/Database.php';

// Initialize database connection
$database = new Database();
$db = $database->connect();

// Retrieve the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Ensure that answers and quiz_id are present in the request
if (!isset($data['answers']) || !isset($data['quiz_id'])) {
    http_response_code(400); // Bad request
    echo json_encode(['error' => 'Invalid request data']);
    exit;
}

$answers = $data['answers'];
$quizId = $data['quiz_id'];
$userId = $_SESSION['user_id']; // Ensure the user is logged in

try {
    // Begin a transaction
    $db->beginTransaction();

    $correctCount = 0; // Initialize correct answers count

    foreach ($answers as $answer) {
        $questionId = $answer['question_id'];
        $answerId = $answer['answer_id'];
        $isCorrect = $answer['is_correct']; // Passed from the client side (0 or 1)

        // Increment correct count if the answer is correct
        if ($isCorrect == 1) {
            $correctCount++;
        }

        // Insert the user's answer into a quiz_attempts table
        $stmt = $db->prepare("
            INSERT INTO quiz_attempts (user_id, quiz_id, question_id, answer_id, is_correct, attempt_date) 
            VALUES (:user_id, :quiz_id, :question_id, :answer_id, :is_correct, NOW())
        ");

        $stmt->execute([
            ':user_id' => $userId,
            ':quiz_id' => $quizId,
            ':question_id' => $questionId,
            ':answer_id' => $answerId,
            ':is_correct' => $isCorrect
        ]);
    }

    // Insert total correct count into user_scores table
    $scoreStmt = $db->prepare("
        INSERT INTO user_scores (user_id, quiz_id, correct_count, attempt_date)
        VALUES (:user_id, :quiz_id, :correct_count, NOW())
    ");

    $scoreStmt->execute([
        ':user_id' => $userId,
        ':quiz_id' => $quizId,
        ':correct_count' => $correctCount
    ]);

    // Commit the transaction
    $db->commit();

    // Send success response with correct count and user ID
    http_response_code(200);
    echo json_encode([
        'message' => 'Answers submitted successfully',
        'user_id' => $userId,
        'correct_count' => $correctCount
    ]);
} catch (Exception $e) {
    // Roll back the transaction on error
    $db->rollBack();
    http_response_code(500); // Internal server error
    echo json_encode(['error' => 'Failed to submit answers', 'details' => $e->getMessage()]);
    exit;
}
?>
