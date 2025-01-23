<?php include "./layout/sidebar.php";?>
<div class="container mx-auto p-6 h-screen mr-40">
        <h1 class="text-3xl font-bold text-center mb-6">Create a Quiz</h1>

        <div class="bg-white p-10 rounded-lg shadow-md h-full overflow-y-auto max-w-4xl mx-auto mr-60">
            <form id="quizForm" action="../../Model/save_quiz.php" method="POST" class="space-y-4">
                <!-- Quiz Title -->
                <div>
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
                <div id="questionsContainer" class="space-y-4">
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
                <div>
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
                    class="w-full bg-blue-500 text-white py-3 px-6 rounded-lg hover:bg-blue-600">
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
