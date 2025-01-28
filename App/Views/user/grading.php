<?php
require_once '../../Database/Database.php';
require_once '../../Controller/UserModel.php';

$database = new Database();
$db = $database->connect();
$userModel = new UserModel($db);
session_start();

// Get the user_id from the session
$user_id = $_SESSION['user_id'];

// Fetch user grades and user details
$stmt = $userModel->getUserGrades($user_id);
$totalGrades = 0;
$gradeCount = 0;
$hasCompleteGrade = true; // Assume user has complete grades
$hasGradeBelow75 = false; // Assume user doesn't have grades below 75

// User details to check grade and semester
$userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php include_once "./layout/sidebar.php"; ?>
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg text-black">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">User Grades</h1>

    <?php
    // Check if the user's grade is 12 and the semester is 2nd Semester
    $isGrade12SecondSemester = $userDetails['user_grade'] == 12 && $userDetails['semester'] == '2nd Semester';
    
    // If the condition is met, show a message that explains the restriction
    if ($isGrade12SecondSemester) {
        echo '<p class="text-red-500">You cannot view grades as you are in 12th grade in the 2nd Semester.</p>';
    }

    // Display the grades for the user, regardless of the condition above
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
            // Ensure that we fetched valid user details and grades
            if ($userDetails !== false): // Check if fetch was successful
                do {
                    echo '<tr class="hover:bg-gray-100">';
                    echo '<td class="py-2 px-4 border-b">' . htmlspecialchars($userDetails['id']) . '</td>'; // user ID
                    echo '<td class="py-2 px-4 border-b">' . htmlspecialchars($userDetails['surname']) . '</td>'; // surname
                    echo '<td class="py-2 px-4 border-b">' . htmlspecialchars($userDetails['user_grade']) . '</td>'; // user grade from users table
                    echo '<td class="py-2 px-4 border-b">' . htmlspecialchars($userDetails['grade_from_user_grades']) . '</td>'; // grade from user_grades

                    // Sum grades and check for conditions
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

        <!-- Check if the average or any grade is below 75 to disable the Enroll button -->
        <?php 
        $isEnrollDisabled = $hasGradeBelow75 || $averageGrade < 75 || $isGrade12SecondSemester;
        ?>

        <!-- Display the Enroll button only if the user is not in grade 12 and 2nd semester and no grades are below 75 -->
        <div class="mt-6">
            <a href="enroll.php" 
                class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 <?= $isEnrollDisabled ? 'opacity-50 cursor-not-allowed' : '' ?>" 
                <?= $isEnrollDisabled ? 'disabled' : '' ?>>
                Enroll
            </a>
        </div>

    <?php else: ?>
        <p class="text-red-500">No grades found for this user.</p>
    <?php endif; ?>
</div>  
