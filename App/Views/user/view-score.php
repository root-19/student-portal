<?php
session_start();
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Controller/Usermodel.php';

$database = new Database();
$db = $database->connect();
$userModel = new UserModel($db);

// Fetch current user's ID from session
$userId = $_SESSION['user_id'];

// Query to fetch the user's scores
$stmt = $db->prepare("SELECT qs.quiz_id, qs.correct_count, q.title 
                      FROM user_scores qs 
                      JOIN quizzes q ON qs.quiz_id = q.id 
                      WHERE qs.user_id = :user_id");
$stmt->execute([':user_id' => $userId]);
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php include "./layout/sidebar.php"; ?>
<?php if (!empty($scores)): ?>
    <div class="max-w-5xl mx-auto py-20 px-8 mr-60">
        <span class="text-3xl font-semibold text-center text-gray-800">Your Quiz Scores</span>
          <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Quiz Title</th>
                    <th scope="col" class="px-6 py-3">Correct Answers</th>
                    <th scope="col" class="px-6 py-3">Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($scores as $score): ?>
                    <?php
                        // Calculate percentage based on correct answers and total questions
                        $totalQuestions = 10; // Assuming 10 questions per quiz for calculation
                        $percentage = ($score['correct_count'] / $totalQuestions) * 100;
                    ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-4 px-6 text-gray-700"><?php echo htmlspecialchars($score['title']); ?></td>
                        <td class="py-4 px-6 text-gray-700"><?php echo $score['correct_count']; ?></td>
                        <td class="py-4 px-6 text-gray-700"><?php echo number_format($percentage, 2); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-xl text-center text-gray-700">No scores available. Please take a quiz!</p>
<?php endif; ?>

</body>
</html>
