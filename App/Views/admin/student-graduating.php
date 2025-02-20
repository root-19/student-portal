<?php

require_once '../../Database/Database.php'; 
require_once '../../Controller/UserModel.php';

$database = new Database();
$db = $database->connect(); 
$userModel = new UserModel($db);

$role = isset($_GET['role']) ? $_GET['role'] : 'user';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch users who are in Grade 12 and 2nd Semester
$users = $userModel->getUsersByGradeSemesterAndSearch(12, '2nd Semester', $searchTerm);

// Group users by section
$groupedUsers = [];
foreach ($users as $user) {
    $groupedUsers[$user['section']][] = $user;
}

?>

<?php include_once "./layout/sidebar.php"; ?>
<div class="container mx-auto mt-10">
    <h1 class="text-3xl font-semibold mb-6 text-gray-800">Grade 12 - 2nd Semester Students</h1>

    <!-- Search Bar -->
    <div class="mb-4 flex justify-between items-center">
        <form method="GET" action="" class="w-full max-w-lg flex space-x-2">
            <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" 
                   class="px-4 py-2 w-full text-black border border-gray-300 rounded-lg" 
                   placeholder="Search by Student ID or Name...">
            <button type="submit" class="px-4 py-2 bg-maroon-500 text-white rounded-lg hover:bg-maroon-600">
                Search
            </button>
        </form>
    </div>

    <?php if (!empty($groupedUsers)): ?>
        <!-- Display users grouped by section -->
        <?php foreach ($groupedUsers as $section => $students): ?>
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Section <?= htmlspecialchars($section) ?></h2>
                <div class="overflow-y-auto max-h-96 border border-gray-300 rounded-lg">
                    <table class="min-w-full table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Student ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Surname</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Scholar</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr class="hover:bg-gray-100">
                                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($student['school_id']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($student['surname']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($student['scholar']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($student['email']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="mt-4 text-lg text-gray-600">No Grade 12 students found for the 2nd Semester.</p>
    <?php endif; ?>
</div>
