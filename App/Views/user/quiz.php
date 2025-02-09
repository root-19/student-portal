<?php
session_start(); // Ensure session is started if using session variables
require_once __DIR__ . '/../../Model/Quiz.php';
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Controller/Usermodel.php'; 

$database = new Database();
$db = $database->connect();
$userModel = new UserModel($db);

$quiz = new Quiz($db);

// Fetch current user's section and strand
$userId = $_SESSION['user_id']; // Fetch user ID from session
$user = $userModel->getUserById($userId);

if ($user) {
    $userSection = $user['section'];
    $userStrand = $user['strand'];

    $stmt = $db->prepare("
        SELECT q.id, q.title 
        FROM quizzes q
        INNER JOIN quiz_targets qt ON q.id = qt.quiz_id
        WHERE qt.section = ? AND qt.strand = ?
    ");
    $stmt->execute([$userSection, $userStrand]);
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $quizzes = [];
}
?>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include "./layout/sidebar.php"; ?>
<style>
/* Hide scrollbar but keep scrolling functionality */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;  /* Hide scrollbar for IE and Edge */
    scrollbar-width: none;  /* Hide scrollbar for Firefox */
}
</style>

<div class="max-w-4xl mx-auto py-8 overflow-y-auto max-h-[80vh] scrollbar-hide">

    <h1 class="text-4xl font-semibold mb-12 text-center text-green-600">Available Quizzes</h1>

    <form id="quizForm" method="POST" class="space-y-8">
        <?php foreach ($quizzes as $quizData): ?>
            <div class="bg-white rounded-lg shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-105 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($quizData['title']); ?></h2>
                
                <?php
                // Fetch questions for this quiz
                $questionStmt = $db->prepare("SELECT q.id, q.question_text FROM questions q WHERE q.quiz_id = ?");
                $questionStmt->execute([$quizData['id']]);
                $questions = $questionStmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($questions as $question):
                ?>
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <div class="text-lg font-medium text-green-800 mb-3">
                            <strong>Question: </strong><?php echo htmlspecialchars($question['question_text']); ?>
                        </div>

                        <!-- Display answers as radio buttons styled as buttons -->
                        <div class="space-y-3">
                            <?php
                            // Get the answers for this question
                            $answerStmt = $db->prepare("SELECT a.id, a.answer_text, a.is_correct FROM answers a WHERE a.question_id = ?");
                            $answerStmt->execute([$question['id']]);
                            $answers = $answerStmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($answers as $answer):
                            ?>
                                <label class="inline-flex items-center relative">
                                    <input type="radio" name="answer_<?php echo $question['id']; ?>" value="<?php echo $answer['id']; ?>" data-correct="<?php echo $answer['is_correct']; ?>" class="hidden peer" required>
                                    <span class="cursor-pointer bg-gray-200 text-gray-800 px-6 py-3 rounded-full w-full text-center transition duration-300 ease-in-out transform hover:bg-green-500 hover:text-white hover:scale-105 peer-checked:bg-blue-500 peer-checked:text-white peer-checked:scale-105 peer-checked:ring-4 peer-checked:ring-blue-300 focus:outline-none">
                                        <?php echo htmlspecialchars($answer['answer_text']); ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="text-center mt-8">
            <button type="submit" class="bg-green-800 via-purple-500 to-pink-500 text-white text-lg font-semibold py-3 px-8 rounded-full shadow-lg hover:shadow-2xl focus:outline-none focus:ring-4 focus:ring-indigo-300 transform transition duration-300 ease-in-out">
                Submit Answers
            </button>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Set a timeout duration (2 minutes)
const timeoutDuration = 1 * 60 * 1000; // 2 minutes in milliseconds

let timeout;

// Function to reset the timeout
function resetTimeout() {
    // Clear any existing timeouts
    clearTimeout(timeout);
    
    // Set a new timeout that redirects the user after the inactivity duration
    timeout = setTimeout(function() {
        window.location.href = "../../index.php"; // Redirect to index.php
    }, timeoutDuration);
}

// Listen for any user activity (mousemove, keydown, or clicks)
document.addEventListener('mousemove', resetTimeout);
document.addEventListener('keydown', resetTimeout);
document.addEventListener('click', resetTimeout);

// Initial call to start the timeout tracking
resetTimeout();
</script>
<script>document.getElementById('quizForm').addEventListener('submit', function (event) {
    event.preventDefault();

    let answers = [];
    const formData = new FormData(this);

    formData.forEach(function (value, key) {
        let questionId = key.split('_')[1]; // Extract question ID
        let isCorrect = document.querySelector(`input[name='${key}']:checked`).dataset.correct;

        answers.push({
            question_id: questionId,
            answer_id: value,
            is_correct: isCorrect
        });
    });

    fetch('../../Model/submit_quiz.php', {
        method: 'POST',
        body: JSON.stringify({
            answers: answers,
            quiz_id: <?php echo $quizData['id']; ?>
        }),
        headers: {
            'Content-Type': 'application/json',
        },
    })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Your answers have been submitted!',
                    text: 'Thank you for participating!',
                    confirmButtonText: 'OK'

                }).then((result) => {
                    if(result.isCorrect) {
                        window.location.href = './index.php';
                    }
                });
            } else if (data.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: data.error,
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: 'There was an error submitting your answers. Please try again.',
                confirmButtonText: 'OK'
            });
        });
});

</script>