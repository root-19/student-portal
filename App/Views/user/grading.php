<?php
require_once '../../Database/Database.php';
require_once '../../Controller/UserModel.php';

$database = new Database();
$db = $database->connect();
$userModel = new UserModel($db);
session_start();

$user_id = $_SESSION['user_id'];


$stmt = $userModel->getUserGrades($user_id);
$totalGrades = 0;
$gradeCount = 0;
$hasCompleteGrade = true; 
$hasGradeBelow75 = false; 


$userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    if ($userDetails) {
        $name = $userDetails['name'];
        $surname = $userDetails['surname'];
        $scholar = (bool) $userDetails['scholar'];
        $school_id = $userDetails['school_id'];
        $fee = $scholar ? 0 : 7000;

        try {
            // Insert enrollment record
            $query = "INSERT INTO enrollments (user_id, name, surname, fee, scholar_status, school_id, enrollment_status)
                      VALUES (:user_id, :name, :surname, :fee, :scholar_status, :school_id, 'pending')";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':surname', $surname);
            $stmt->bindParam(':fee', $fee);
            $stmt->bindParam(':scholar_status', $scholar, PDO::PARAM_BOOL);
            $stmt->bindParam(':school_id', $school_id);
            $stmt->execute();

            if (!$scholar) {
                // Redirect to PayMongo payment page for non-scholars
                header("Location: ../../../../Model/paymongo.php?user_id=$user_id&fee=$fee");
                exit;
            } else {
                // For scholars, show success message using SweetAlert
                echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Enrollment Request Submitted',
                            text: 'You are a scholar. Your enrollment is pending approval.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../../Views/user/index.php';
                            }
                        });
                    </script>";
            }
        } catch (Exception $e) {
            echo "<p class='text-red-500'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
}
?>

<?php include_once "./layout/sidebar.php"; ?>
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg text-black">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">User Grades</h1>

    <?php
  
    $isGrade12SecondSemester = $userDetails['user_grade'] == 12 && $userDetails['semester'] == '2nd Semester';
    
   
    if ($isGrade12SecondSemester) {
        echo '<p class="text-red-500">You cannot view grades as you are in 12th grade in the 2nd Semester.</p>';
    }
    if ($stmt->rowCount() > 0): ?>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b">User ID</th>
                    <th class="py-2 px-4 border-b">Surname</th>
                    <th class="py-2 px-4 border-b">User Grade</th>
                    <th class="py-2 px-4 border-b">Grade from User Grades</th>
                </tr>
            </thead>
            <tbody>
            <?php
           
            if ($userDetails !== false): 
                do {
                    echo '<tr class="hover:bg-gray-100">';
                    echo '<td class="py-2 px-4 border-b">' . htmlspecialchars($userDetails['id']) . '</td>';
                    echo '<td class="py-2 px-4 border-b">' . htmlspecialchars($userDetails['surname']) . '</td>';
                    echo '<td class="py-2 px-4 border-b">' . htmlspecialchars($userDetails['user_grade']) . '</td>';
                    echo '<td class="py-2 px-4 border-b">' . htmlspecialchars($userDetails['grade_from_user_grades']) . '</td>';
                    $totalGrades += $userDetails['grade_from_user_grades'];
                    $gradeCount++;

                    // Check if grade is below 75
                    if ($userDetails['grade_from_user_grades'] < 75) {
                        $hasGradeBelow75 = true;
                    }

                } while ($userDetails = $stmt->fetch(PDO::FETCH_ASSOC));
            else:
                echo '<tr><td colspan="4" class="text-red-500">No data found for this user.</td></tr>';
            endif;
            ?>
            </tbody>
        </table>

        <!-- Display Average Grade -->
        <div class="mt-4">
            <?php 
            if ($gradeCount > 0): 
                $averageGrade = $totalGrades / $gradeCount;
                ?>
                <p class="text-xl font-semibold text-gray-800">Average Grade: <?= round($averageGrade, 2) ?></p>
            <?php else: ?>
                <p class="text-red-500">No grades available for calculation.</p>
            <?php endif; ?>
        </div>


        <?php 
        $isEnrollDisabled = $hasGradeBelow75 || $averageGrade < 75 || $isGrade12SecondSemester;
        ?>

       
<div class="mt-6">
<form method="POST" action="">

                <button type="submit" name="enroll" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 <?= $isEnrollDisabled ? 'opacity-50 cursor-not-allowed' : '' ?>" <?= $isEnrollDisabled ? 'disabled' : '' ?>>
                    Enroll now
                </button>
            </form>
        </div>

    <?php else: ?>
        <p class="text-red-500">No grades found for this user.</p>
    <?php endif; ?>
</div>  
