<?php
require_once __DIR__ . '/../../Model/src/QuizAssigment.php';
require_once __DIR__ . '/../../Model/src/Quiz.php';

// Create instances of the classes
$quizAssignment = new QuizAssigment();
$quiz = new Qiuz();

// Fetch available quizzes (active quizzes)
$activeQuizzes = $quiz->getActiveQuizzes();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Assign Quiz to Section and Strand</h1>

    <!-- Form to assign quiz -->
    <form action="assign_quiz.php" method="POST" class="bg-white p-6 shadow-lg rounded-lg">
        <div class="mb-4">
            <label for="quiz_id" class="block text-sm font-medium text-gray-700">Select Quiz</label>
            <select id="quiz_id" name="quiz_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <?php foreach ($activeQuizzes as $quizItem): ?>
                    <option value="<?= $quizItem['id']; ?>"><?= $quizItem['title']; ?> - <?= $quizItem['description']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label for="strand" class="block text-sm font-medium text-gray-700">Select Strand</label>
            <input type="text" id="strand" name="strand" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <div class="mb-4">
            <label for="section" class="block text-sm font-medium text-gray-700">Select Section</label>
            <input type="text" id="section" name="section" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>

        <!-- Correct Answers (You can add more fields as needed for the quiz answers) -->
        <div class="mb-4">
            <label for="answers" class="block text-sm font-medium text-gray-700">Enter Correct Answers</label>
            <textarea id="answers" name="answers" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" rows="4" placeholder="Enter answers here..." required></textarea>
        </div>

        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Assign Quiz</button>
    </form>
</div>

</body>
</html>
