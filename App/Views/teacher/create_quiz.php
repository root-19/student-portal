
<?php include "./layout/sidebar.php";?>
<div class="container mx-auto p-6 h-screen mb-40 ml-20">
        <h1 class="text-3xl font-bold text-center mb-6 text-black">Create a Quiz</h1>

        <div class="bg-white p-10 rounded-lg shadow-md h-full overflow-y-auto max-w-4xl mx-auto mr-60">
            <form id="quizForm" action="../../Model/save_quiz.php" method="POST" class="space-y-4">
                <!-- Quiz Title -->
                <div class="text-black">
                    <label for="quizTitle" class="block text-lg font-medium text-gray-700">Quiz Title</label>
                    <input 
                        type="text" 
                        id="quizTitle" 
                        name="quizTitle" 
                        class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Enter quiz title" 
                        required
                    >
                </div>

                <!-- Questions Section -->
                <div id="questionsContainer" class="space-y-4 text-black">
                    <div class="questionItem">
                        <label class="block text-lg font-medium text-gray-700">Question 1</label>
                        <input 
                            type="text" 
                            name="questions[]" 
                            class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                            placeholder="Enter question" 
                            required
                        >
                        <div class="answersContainer mt-4 space-y-2">
                            <div class="flex items-center space-x-2">
                                <input 
                                    type="text" 
                                    name="answers[1][]" 
                                    class="flex-1 p-2 border border-gray-300 rounded-md" 
                                    placeholder="Enter answer option" 
                                    required
                                >
                                <label>
                                    <input type="checkbox" name="correct[1][]" class="ml-2"> Correct
                                </label>
                            </div>
                        </div>
                        <button 
                            type="button" 
                            class="mt-2 text-sm text-blue-500 hover:underline" 
                            onclick="addAnswerOption(1)">
                            + Add another answer
                        </button>
                    </div>
                </div>

                <!-- Add Question Button -->
                <button 
                    type="button" 
                    class="block w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600"
                    onclick="addQuestion()">
                    + Add another question
                </button>

                <!-- Section Dropdown -->
                <div>
                    <label for="section" class="block text-lg font-medium text-black">Target Section</label>
                    <select 
                        id="section" 
                        name="section" 
                        class="w-full mt-2 text-black p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                        required>
                        <option value="">Select Section</option>
                        <!-- Sections will be dynamically populated -->
                    </select>
                </div>

                <!-- Strand Dropdown -->
                <div class="text-black">
                    <label for="strand" class="block text-lg font-medium text-gray-700">Target Strand</label>
                    <select 
                        id="strand" 
                        name="strand" 
                        class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                        required>
                        <option value="">Select Strand</option>
                        <!-- Strands will be dynamically populated -->
                    </select>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-green-800 text-white py-3 px-6 rounded-lg hover:bg-green-600">
                    Create Quiz
                </button>
            </form>
        </div>
    </div>

    <script>
    let questionCount = 1;

    // Function to add a new question
    function addQuestion() {
        questionCount++;
        const questionsContainer = document.getElementById('questionsContainer');

        const questionHTML = `
            <div class="questionItem">
                <label class="block text-lg font-medium text-gray-700">Question ${questionCount}</label>
                <input 
                    type="text" 
                    name="questions[]" 
                    class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                    placeholder="Enter question" 
                    required
                >
                <div class="answersContainer mt-4 space-y-2">
                    <div class="flex items-center space-x-2">
                        <input 
                            type="text" 
                            name="answers[${questionCount}][]" 
                            class="flex-1 p-2 border border-gray-300 rounded-md" 
                            placeholder="Enter answer option" 
                            required
                        >
                        <label>
                            <input type="checkbox" name="correct[${questionCount}][]" class="ml-2"> Correct
                        </label>
                    </div>
                </div>
                <button 
                    type="button" 
                    class="mt-2 text-sm text-blue-500 hover:underline" 
                    onclick="addAnswerOption(${questionCount})">
                    + Add another answer
                </button>
            </div>
        `;
        questionsContainer.insertAdjacentHTML('beforeend', questionHTML);
    }

    // Function to add an answer option to a specific question
    function addAnswerOption(questionNumber) {
        const answersContainer = document.querySelector(
            `.questionItem:nth-child(${questionNumber}) .answersContainer`
        );

        const answerHTML = `
            <div class="flex items-center space-x-2">
                <input 
                    type="text" 
                    name="answers[${questionNumber}][]" 
                    class="flex-1 p-2 border border-gray-300 rounded-md" 
                    placeholder="Enter answer option" 
                    required
                >
                <label>
                    <input type="checkbox" name="correct[${questionNumber}][]" class="ml-2"> Correct
                </label>
            </div>
        `;
        answersContainer.insertAdjacentHTML('beforeend', answerHTML);
    }

    // Event listener for form submission
   
   
    // Populate sections and strands from API
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const response = await fetch('get_sections_and_strands.php');
            if (!response.ok) {
                throw new Error('Failed to fetch sections and strands.');
            }
            const data = await response.json();

            // Populate Section Dropdown
            const sectionSelect = document.getElementById('section');
            data.sections.forEach((section) => {
                const option = document.createElement('option');
                option.value = section;
                option.textContent = section;
                sectionSelect.appendChild(option);
            });

            // Populate Strand Dropdown
            const strandSelect = document.getElementById('strand');
            data.strands.forEach((strand) => {
                const option = document.createElement('option');
                option.value = strand;
                option.textContent = strand;
                strandSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error fetching sections and strands:', error);
        }
    });
</script>
=======
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
>>>>>>> 2be30f9 (push)
