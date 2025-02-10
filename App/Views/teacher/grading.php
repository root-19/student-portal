<?php
require_once '../../Database/Database.php';
require_once '../../Controller/UserModel.php';

$database = new Database();
$db = $database->connect();
$userModel = new UserModel($db);

// Fetch users with advisers
$results = $userModel->UsersWithAdvisers();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save grades into the database
    $userId = $_POST['user_id']; // The primary key from the `users` table
    $grades = $_POST['grades'] ?? [];
    
    foreach ($grades as $subject => $grade) {
        // Insert grade into the database
        $stmt = $db->prepare("INSERT INTO user_grades (user_id, subject, grade) VALUES (:user_id, :subject, :grade)");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT); // Bind as integer
        $stmt->bindParam(':subject', $subject, PDO::PARAM_STR); // Bind as string
        $stmt->bindParam(':grade', $grade, PDO::PARAM_STR); // Bind as string/float
        $stmt->execute();
    }

    echo '<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "Success!",
            text: "Grades saved successfully!",
            icon: "success",
            confirmButtonText: "OK"
        }).then(() => {
            window.location.href = "index.php"; // Redirect after alert
        });
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
                // Extract user details
                $userId = $row['user_school_id']; // The actual user ID from the database
                $userName = $row['user_name'] . ' ' . $row['user_surname'];
                $grade = $row['user_grade'];
                $strand = strtolower($row['user_strand']);
                $semester = $row['user_semester'];

                // Fetch predefined subjects
                $subjects = $userModel->getPredefinedSubjects()[$grade][$strand][$semester] ?? [];
                ?>
                <form action="" method="POST" class="bg-white p-4 rounded-lg shadow-md">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">

                    <h2 class="text-lg font-semibold">
                        Name: <?php echo htmlspecialchars($userName); ?> |
                        Grade: <?php echo htmlspecialchars($grade); ?> |
                        Strand: <?php echo htmlspecialchars($strand); ?> |
                        Semester: <?php echo htmlspecialchars($semester); ?>
                    </h2>

                    <!-- Display Subjects and Input for Grades -->
                    <table class="min-w-full divide-y divide-gray-200 mt-4">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-left px-4 py-2">Subject</th>
                                <th class="text-left px-4 py-2">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($subjects as $subject) : ?>
                                <tr>
                                    <td class="px-4 py-2"><?php echo htmlspecialchars($subject); ?></td>
                                    <td class="px-4 py-2">
                                        <input type="number" name="grades[<?php echo htmlspecialchars($subject); ?>]" 
                                               class="border border-gray-300 rounded px-2 py-1 w-20" 
                                               min="0" max="100" step="0.01" required>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <button type="submit" class="mt-4 bg-green-800 text-white px-4 py-2 rounded">
                        Submit Grades
                    </button>
                </form>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-gray-500">No users found to assign grades.</div>
        <?php endif; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
