<?php
// Include the necessary files
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Model/SubjectController.php';


// Start the session
session_start();

// Create a Database instance and pass it to the SubjectController
$db = new Database();  // Create Database object
$subjectController = new SubjectController($db);  // Pass the Database object to the SubjectController

// Assuming you're using a session to get the userId
$userId = $_SESSION['user_id'] ?? null;  // Get user_id from session

if ($userId) {
    // Fetch the user data
    $userData = $subjectController->getUser($userId);

    if ($userData && !isset($userData['error'])) {
        $grade = $userData['grade'];
        $strand = $userData['strand'];

        // Get subjects for the user's grade and strand
        $subjects = $subjectController->getSubjects($grade, $strand);
    } else {
        $subjects = [];
    }
} else {
    // Handle the case when the user is not logged in
    $subjects = [];
}
?><?php include './layout/sidebar.php'; ?>

<!-- <div class="flex min-h-screen mr-60 "> -->
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


    <!-- Main Content -->
    <div class="flex-2  p-20 max-h-screen overflow-y-auto max-h-[80vh] scrollbar-hide  ">
    <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h1 class="text-2xl font-bold text-center text-gray-800">Your Subjects for All Semesters</h1>

    <?php if (empty($subjects)): ?>
        <div class="p-4 bg-red-100 border-l-4 border-red-500 text-gray-700">
            <p class="text-center">No subjects available for your grade and strand.</p>
        </div>
    <?php else: ?>
        <?php foreach ($subjects as $semester => $semesterSubjects): ?>
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-700 border-b pb-2"><?= htmlspecialchars($semester); ?></h2>
                <div class="space-y-2 mt-4">
                    <?php foreach ($semesterSubjects as $subject): ?>
                        <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg border-l-4 border-green-500">
                            <p class="text-lg text-gray-800"><?= htmlspecialchars($subject); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
