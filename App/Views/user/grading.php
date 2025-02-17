<?php
require_once '../../Database/Database.php';
require_once '../../Controller/UserModel.php';

session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<p class='text-red-500'>Access Denied. Please log in.</p>");
}

$database = new Database();
$db = $database->connect();
$userModel = new UserModel($db);

$user_id = $_SESSION['user_id'];
$stmt = $userModel->getUserGrades($user_id);

$totalGrades = 0;
$gradeCount = 0;
$hasGradeBelow75 = false;
$averageGrade = 0;

$userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    if ($userDetails) {
        $name = $userDetails['name'];
        $surname = $userDetails['surname'];
        $scholar =  $userDetails['scholar']; // Ensure integer value
        $school_id = $userDetails['school_id'];
        $fee = ($scholar === 'scholar') ? 0 : 7000; // Determine fee

        try {
            $query = "INSERT INTO enrollments 
                        (user_id, name, surname, fee, scholar_status, school_id, enrollment_status)
                      VALUES 
                        (:user_id, :name, :surname, :fee, :scholar_status, :school_id, 'pending')";
            $stmtInsert = $db->prepare($query);
            $stmtInsert->bindParam(':user_id', $user_id);
            $stmtInsert->bindParam(':name', $name);
            $stmtInsert->bindParam(':surname', $surname);
            $stmtInsert->bindParam(':fee', $fee);
            $stmtInsert->bindParam(':scholar_status', $scholar);
            $stmtInsert->bindParam(':school_id', $school_id);
            $stmtInsert->execute();

            // If the user is required to pay (i.e., scholar status is 'paid')
            if ($scholar === 'pay') {
                header("Location: ../../../../Model/paymongo.php?user_id=$user_id&fee=$fee");
                exit;
            } else {
                // Output a complete HTML document with SweetAlert for scholar users
                ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Enrollment Submitted</title>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Enrollment Submitted',
                            text: 'You are a scholar. Your enrollment request has been submitted for approval.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'grading.php';
                        });
                    </script>
                </body>
                </html>
                <?php
                exit;
            }
        } catch (Exception $e) {
            // Output error message via SweetAlert
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Error</title>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred: <?php echo htmlspecialchars($e->getMessage(), ENT_QUOTES, "UTF-8"); ?>'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                </script>
            </body>
            </html>
            <?php
            exit;
        }
    }
}
?>

<?php include_once "./layout/sidebar.php"; ?>
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg text-black">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Grades</h1>

    <?php
    $isGrade12SecondSemester = ($userDetails['user_grade'] == 12 && $userDetails['semester'] == '2nd Semester');
    
    if ($isGrade12SecondSemester) {
        echo '<p class="text-red-500">Enrollment is not allowed for Grade 12 (2nd Semester) students.</p>';
    }

    if ($stmt->rowCount() > 0): ?>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <!-- <th class="py-2 px-4 border-b">User ID</th> -->
                    <th class="py-2 px-4 border-b">Surname</th>
                    <th class="py-2 px-4 border-b">User Grade</th>
                    <th class="py-2 px-4 border-b">Grade</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr class="hover:bg-gray-100">
                    <!-- <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['id']) ?></td> -->
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['surname']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['user_grade']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['grade_from_user_grades']) ?></td>
                </tr>
                <?php
                $totalGrades += $row['grade_from_user_grades'];
                $gradeCount++;
                if ($row['grade_from_user_grades'] < 75) {
                    $hasGradeBelow75 = true;
                }
                ?>
            <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Display Average Grade -->
        <div class="mt-4">
            <?php if ($gradeCount > 0): 
                $averageGrade = $totalGrades / $gradeCount;
                ?>
                <p class="text-xl font-semibold text-gray-800">Average Grade: <?= round($averageGrade, 2) ?></p>
            <?php else: ?>
                <p class="text-red-500">No grades available for calculation.</p>
            <?php endif; ?>
        </div>

        <?php $isEnrollDisabled = $hasGradeBelow75 || $averageGrade < 75 || $isGrade12SecondSemester; ?>

        <div class="mt-6">
            <form method="POST" action="">
                <button type="submit" name="enroll" 
                    class="px-6 py-3 bg-green-800 text-white rounded-lg hover:bg-green-600 <?= $isEnrollDisabled ? 'opacity-50 cursor-not-allowed' : '' ?>" 
                    <?= $isEnrollDisabled ? 'disabled' : '' ?>>
                    Enroll Now
                </button>
            </form>
        </div>

        <!-- Debugging JavaScript -->
        <script>
            document.querySelector("form").addEventListener("submit", function() {
                console.log("Form is submitting...");
            });

            document.querySelector("button[name='enroll']").addEventListener("click", function() {
                console.log("Enroll button clicked.");
            });
        </script>

    <?php else: ?>
        <p class="text-red-500">No grades found for this user.</p>
    <?php endif; ?>
</div>