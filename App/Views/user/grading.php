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

// Ensure $userDetails exists before accessing properties
$userGrade = $userDetails ? $userDetails['user_grade'] : null;
$semester = $userDetails ? $userDetails['semester'] : null;
$isGrade12SecondSemester = ($userGrade == 12 && $semester == '2nd Semester');
// $isGrade12SecondSemester = ($userGrade == 12 && $semester == '2nd Semester');

// This code block handles the enrollment process for a user. It checks if the request method is POST and if the 'enroll' form field is set. It then retrieves the user details from the database and determines the appropriate enrollment fee based on the user's scholar status. The code then inserts the enrollment details into the 'enrollments' table in the database.

// If the user is a scholar (QVR-Voucher or ESC), the code displays a success message using SweetAlert. If the user is an ALS/Balik or ESC scholar, the code redirects the user to the PayMongo payment page with the appropriate fee.

// If an exception occurs during the enrollment process, the code displays an error message using SweetAlert.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    if ($userDetails) {
        $name = $userDetails['name'];
        $surname = $userDetails['surname'];
        $scholar = $userDetails['scholar'];
        $school_id = $userDetails['school_id'];

        // Determine fee based on scholar status
        if ($scholar === 'QVR-Voucher') {
            $fee = 0;
        } elseif ($scholar === 'ESC') {
            $fee = 7000 * 0.5; // 50% discount for ESC
        } else { // ALS/Balik or Pay
            $fee = 7000;
        }

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

            // Handle the payment redirection or success alert
            if ($scholar === 'ALS/Balik' || $scholar === 'ESC') {
                // Redirect to PayMongo with appropriate fee
                header("Location: ../../../../Model/paymongo.php?user_id=$user_id&fee=$fee");
                exit;
            } else {
                // Display SweetAlert for QVR-Voucher or Scholar status
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
                            text: 'You are a scholar (QVR-Voucher). Your enrollment request has been submitted for approval.',
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
            // Display error message using SweetAlert
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
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
</style>
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg text-black  overflow-y-auto max-h-[65vh] px-2 sm:px-4 scrollbar-hide">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Grades</h1>
    
    <?php if (!$userDetails): ?>
        <h1 class="text-5xl text-red-500">You have not received any grades yet. Please wait for your teacher's announcement.</h1>
    <?php elseif ($isGrade12SecondSemester): ?>
        <p class="text-red-500">Enrollment is not allowed for Grade 12 (2nd Semester) students.</p>
    <?php endif; ?>

    <?php if ($isGrade12SecondSemester): ?>
        <p class="text-red-500">Enrollment is not allowed for Grade 12 (2nd Semester) students.</p>
    <?php endif; ?>

    <?php
    // $isGrade12SecondSemester = ($userDetails['user_grade'] == 12 && $userDetails['semester'] == '2nd Semester');
    

    if ($isGrade12SecondSemester) {
        echo '<p class="text-red-500">Enrollment is not allowed for Grade 12 (2nd Semester) students.</p>';
    }
    

    if ($stmt->rowCount() > 0): ?>
     <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
     <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr class="bg-gray-200">
                    <!-- <th class="py-2 px-4 border-b">User ID</th> -->
                    <th scope="col" class="px-6 py-3">Surname</th>
                    <th scope="col" class="px-6 py-3">User Grade</th>
                    <th scope="col" class="px-6 py-3">Grade</th>
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