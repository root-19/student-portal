<?php

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/Quiz.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check and log if the data is received correctly
    $quizTitle = $_POST['quizTitle'];
    $section = $_POST['section'];
    $strand = $_POST['strand'];
    $questions = $_POST['questions'];
    $answers = $_POST['answers'];
    $correctAnswers = $_POST['correct'];

    // Log data to check if the arrays are correct
    // error_log("Quiz Title: $quizTitle");
    // error_log("Section: $section");
    // error_log("Strand: $strand");
    // error_log("Questions: " . json_encode($questions));
    // error_log("Answers: " . json_encode($answers));
    // error_log("Correct Answers: " . json_encode($correctAnswers));

    try {
        // Initialize the database connection
        $database = new Database(); // Make sure the database connection is created
        $db = $database->connect(); // Establish the connection

        // Pass the initialized database connection to the Quiz class
        $quiz = new Quiz($db); // Pass $db as a dependency

        // Save the full quiz and get the quiz ID
        $quizId = $quiz->saveFullQuiz($quizTitle, $section, $strand, $questions, $answers, $correctAnswers);

        // Return success message
     // Redirect upon success
     header("Location: ../Views/teacher/create_quiz.php");
    
        exit();
    } catch (Exception $e) {
        // Handle errors
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}

die()
?>
