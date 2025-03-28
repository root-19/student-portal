
<?php include "./layout/sidebar.php";?>
<div class="container mx-auto p-6 h-screen mb-40 ml-20">
    

        <div class="bg-white p-10 rounded-lg shadow-md h-full overflow-y-auto max-w-4xl mx-auto mr-60">
        <h1 class="text-3xl font-bold text-center mb-6 text-black">Create a Quiz</h1>
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
    <input type="checkbox" name="correct[1][]" value="0" hidden>
    <input type="checkbox" name="correct[1][]" value="1" class="ml-2"> Correct
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
        <div class="questionItem mt-6 p-4 border border-gray-300 rounded-lg">
            <label class="block text-lg font-medium text-gray-700">Question ${questionCount}</label>
            <input 
                type="text" 
                name="questions[]" 
                class="w-full mt-2 p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Enter question" 
                required
            >
            <div class="answersContainer mt-4 space-y-2">
                ${generateAnswerOption(questionCount, 1)}
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

// Function to generate an answer input
function generateAnswerOption(questionNumber, answerNumber) {
    return `
        <div class="flex items-center space-x-2 answerOption">
            <input 
                type="text" 
                name="answers[${questionNumber}][]" 
                class="flex-1 p-2 border border-gray-300 rounded-md" 
                placeholder="Enter answer option" 
                required
            >
            <label class="flex items-center">
                <input 
                    type="hidden" 
                    name="correct[${questionNumber}][]" 
                    value="0"
                >
                <input 
                    type="checkbox" 
                    class="ml-2 correctCheckbox"
                    onclick="handleCorrectAnswerClick(event, ${questionNumber})"
                >
                Correct
            </label>
        </div>
    `;
}

// Function to handle click on correct answer checkbox
function handleCorrectAnswerClick(event, questionNumber) {
    const currentCheckbox = event.target;
    const answerOption = currentCheckbox.closest('.answerOption');
    const hiddenInput = answerOption.querySelector('input[type="hidden"]');
    
    // Get all checkboxes for this question
    const checkboxes = document.querySelectorAll(`.questionItem:nth-child(${questionNumber}) .correctCheckbox`);
    
    // Uncheck and set hidden inputs to 0 for other checkboxes
    checkboxes.forEach((checkbox) => {
        if (checkbox !== currentCheckbox) {
            checkbox.checked = false;
            const otherHiddenInput = checkbox.closest('.answerOption').querySelector('input[type="hidden"]');
            otherHiddenInput.value = "0";
        }
    });

    // Set the hidden input value based on checkbox state
    hiddenInput.value = currentCheckbox.checked ? "1" : "0";
}

// Function to add an answer option to a specific question
function addAnswerOption(questionNumber) {
    const questionItems = document.querySelectorAll('.questionItem');
    const questionItem = questionItems[questionNumber - 1]; 
    const answersContainer = questionItem.querySelector('.answersContainer');

    const answerHTML = generateAnswerOption(questionNumber, answersContainer.children.length + 1);
    answersContainer.insertAdjacentHTML('beforeend', answerHTML);
}

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
