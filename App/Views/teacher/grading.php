<?php
require_once '../../Database/Database.php';
require_once '../../Controller/UserModel.php';
require_once '../../Model/SubjectManager.php';

$database = new Database();
$db = $database->connect();
$userModel = new UserModel($db);
$subjectManager = new SubjectManager();

$results = $userModel->UsersWithAdvisers();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_grades'])) {
    $userId = $_POST['user_id'];
    $grades = $_POST['grades'] ?? [];

    // Delete previous grades only for this specific user
    $deleteStmt = $db->prepare("DELETE FROM user_grades WHERE user_id = :user_id");
    $deleteStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $deleteStmt->execute();

    // Insert the new grades for this user
    foreach ($grades as $subject => $grade) {
        $stmt = $db->prepare("INSERT INTO user_grades (user_id, subject, grade) VALUES (:user_id, :subject, :grade)");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $stmt->bindParam(':grade', $grade, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Show success message
    echo '<script>
        Swal.fire({
            title: "Success!",
            text: "Grades updated successfully!",
            icon: "success",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "profile.php";
        });
    </script>';
}
?>

<?php include "./layout/sidebar.php"; ?>

<div class="container mx-auto px-6 py-8 text-black">
    <h1 class="text-2xl font-bold mb-6">Add Grades</h1>

    <div class="space-y-6 overflow-y-auto max-h-[600px] border border-gray-300 rounded-lg p-4">
        <?php if (!empty($results)) : ?>
            <?php foreach ($results as $row) : ?>
                <?php
                $userId = $row['user_school_id'];
                $userName = $row['user_name'] . ' ' . $row['user_surname'];
                $grade = trim($row['user_grade']);
                $strand = strtoupper(trim($row['user_strand']));
                $semester = trim($row['user_semester']);

                $subjects = $subjectManager->getSubjects($grade, $strand, $semester);

                // Fetch the existing grades for the user
                $existingGradesStmt = $db->prepare("SELECT subject, grade FROM user_grades WHERE user_id = :user_id");
                $existingGradesStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $existingGradesStmt->execute();
                $existingGrades = $existingGradesStmt->fetchAll(PDO::FETCH_KEY_PAIR);
                ?>

                <!-- Separate form for each user -->
                <form action="" method="POST" class="bg-white p-4 rounded-lg shadow-md">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">

                    <h2 class="text-lg font-semibold">
                        Name: <?php echo htmlspecialchars($userName); ?> |
                        Grade: <?php echo htmlspecialchars($grade); ?> |
                        Strand: <?php echo htmlspecialchars($strand); ?> |
                        Semester: <?php echo htmlspecialchars($semester); ?>
                    </h2>

                    <table class="min-w-full divide-y divide-gray-200 mt-4">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left px-4 py-2">Subject</th>
                                <th class="text-left px-4 py-2">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (!empty($subjects)) : ?>
                                <?php foreach ($subjects as $subject) : ?>
                                    <tr>
                                        <td class="px-4 py-2"><?php echo htmlspecialchars($subject); ?></td>
                                        <td class="px-4 py-2">
                                            <input type="number" name="grades[<?php echo htmlspecialchars($subject); ?>]" 
                                                class="border border-gray-300 rounded px-2 py-1 w-20" 
                                                min="0" max="100" step="0.01" 
                                                value="<?php echo isset($existingGrades[$subject]) ? htmlspecialchars($existingGrades[$subject]) : ''; ?>" 
                                                required>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="2" class="px-4 py-2 text-red-500">
                                        No subjects found for this grade, strand, and semester.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <?php if (!empty($subjects)) : ?>
                        <button type="submit" name="submit_grades" class="mt-4 bg-green-800 text-white px-4 py-2 rounded">
                            Submit Grades
                        </button>
                    <?php endif; ?>
                </form>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-gray-500">No users found to assign grades.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
