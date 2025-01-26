<?php
// session_start();
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Controller/Usermodel.php';

$database = new Database();
$db = $database->connect();
$userModel = new UserModel($db);

// Query to fetch all users' scores and their names
$stmt = $db->prepare("SELECT us.quiz_id, us.correct_count, q.title, u.name, u.surname
                      FROM user_scores us
                      JOIN quizzes q ON us.quiz_id = q.id
                      JOIN users u ON us.user_id = u.id");
$stmt->execute();
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "./layout/sidebar.php"; ?>

<?php if (!empty($scores)): ?>
    <div class="max-w-5xl mx-auto py-20 px-8 mr-60">
        <span class="text-3xl font-semibold text-center text-gray-800">All Users' Quiz Scores</span>
        <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-lg mx-auto mt-8">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-4 px-6 text-left text-lg font-semibold text-gray-700">Full Name</th>
                    <th class="py-4 px-6 text-left text-lg font-semibold text-gray-700">Quiz Title</th>
                    <th class="py-4 px-6 text-left text-lg font-semibold text-gray-700">Correct Answers</th>
                    <th class="py-4 px-6 text-left text-lg font-semibold text-gray-700">Percentage</th>
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
                        <td class="py-4 px-6 text-gray-700"><?php echo htmlspecialchars($score['name']) . ' ' . htmlspecialchars($score['name']); ?></td>
                        <td class="py-4 px-6 text-gray-700"><?php echo htmlspecialchars($score['title']); ?></td>
                        <td class="py-4 px-6 text-gray-700"><?php echo $score['correct_count']; ?></td>
                        <td class="py-4 px-6 text-gray-700"><?php echo number_format($percentage, 2); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="text-xl text-center text-gray-700">No scores available.</p>
<?php endif; ?>

</body>
</html>
