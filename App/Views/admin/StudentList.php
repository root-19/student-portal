<?php


require_once '../../Database/Database.php'; 
require_once '../../Controller/UserModel.php';

$database = new Database();
$db = $database->connect(); 
$userModel = new UserModel($db);


$role = isset($_GET['role']) ? $_GET['role'] : 'user';


$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';


$users = $userModel->getUsersByRoleAndSearch($role, $searchTerm);


$groupedUsers = [];
foreach ($users as $user) {
    $groupedUsers[$user['grade']][$user['section']][] = $user;
}
?>


    <?php include_once "./layout/sidebar.php"; ?>
    <div class="h-screen overflow-y-auto p-6">
    <h1 class="text-3xl font-semibold mb-6 text-gray-800">Students Data</h1>

    <!-- Search Bar -->
    <div class="mb-4 flex justify-between items-center">
        <form method="GET" action="" class="w-full max-w-lg flex space-x-2">
            <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" 
                   class="px-4 py-2 w-full text-black border border-gray-300 rounded-lg" 
                   placeholder="Search by Student ID or Name...">
            <button type="submit" class="px-4 py-2 text-white rounded-lg hover:bg-maroon-600">
                Search
            </button>
        </form>
    </div>

    <?php if (!empty($groupedUsers)): ?>
        <!-- Display users grouped by grade and section -->
        <?php foreach ($groupedUsers as $grade => $sections): ?>
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Grade <?= htmlspecialchars($grade) ?></h2>
                <?php foreach ($sections as $section => $students): ?>
                    <div class="mb-4">
                        <h3 class="text-lg font-medium text-gray-600 mb-2">Section <?= htmlspecialchars($section) ?></h3>
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
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="mt-4 text-lg text-gray-600">No users found with the role "<?php echo htmlspecialchars($role); ?>"</p>
    <?php endif; ?>
</div>
