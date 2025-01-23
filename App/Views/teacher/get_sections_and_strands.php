<?php

require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Model/Quiz.php'; // Include the Quiz model

header('Content-Type: application/json');

try {
    // Initialize the database connection
    $database = new Database(); // Make sure the database connection is created
    $db = $database->connect(); // Establish the connection
    if ($db) {
        error_log('Database connection successful.');
    }
    // Pass the initialized database connection to the Quiz class
    $quiz = new Quiz($db); // Pass $db as a dependency

    // Fetch sections and strands
    $sections = $quiz->getSections();
    $strands = $quiz->getStrands();

    // Return the data as JSON
    echo json_encode([
        'sections' => array_column($sections, 'section'),
        'strands' => array_column($strands, 'strand'),
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
