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
    $db->beginTransaction();
    $correctCount = 0;

    foreach ($answers as $answer) {
        $questionId = $answer['question_id'];
        $answerId = $answer['answer_id'];
        $isCorrect = intval($answer['is_correct']);

        if ($isCorrect === 1) {
            $correctCount++;
        }

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

    // Check if the user has already taken the quiz
    $checkStmt = $db->prepare("SELECT id FROM user_scores WHERE user_id = :user_id AND quiz_id = :quiz_id");
    $checkStmt->execute([':user_id' => $userId, ':quiz_id' => $quizId]);
    $existingScore = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingScore) {
        // Update existing score
        $scoreStmt = $db->prepare("
            UPDATE user_scores 
            SET correct_count = :correct_count, attempt_date = NOW() 
            WHERE user_id = :user_id AND quiz_id = :quiz_id
        ");
    } else {
        // Insert new score
        $scoreStmt = $db->prepare("
            INSERT INTO user_scores (user_id, quiz_id, correct_count, attempt_date)
            VALUES (:user_id, :quiz_id, :correct_count, NOW())
        ");
    }

    $scoreStmt->execute([
        ':user_id' => $userId,
        ':quiz_id' => $quizId,
        ':correct_count' => $correctCount
    ]);

    $db->commit();

    http_response_code(200);
    echo json_encode([
        'message' => 'Answers submitted successfully',
        'user_id' => $userId,
        'correct_count' => $correctCount
    ]);
} catch (Exception $e) {
    $db->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while submitting your answers.']);
}
