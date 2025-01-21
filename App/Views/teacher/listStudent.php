<?php
require_once '../../Database/Database.php'; 
require_once '../../Controller/UserModel.php';

$database = new Database();
$db = $database->connect(); 
$userModel = new UserModel($db);

// Initialize $results variable
$results = [];

$searchSchoolId = '';
if (isset($_GET['school_id'])) {
    $searchSchoolId = $_GET['school_id'];
    $results = $userModel->getUsersWithAdvisers($searchSchoolId);
} else {
    $results = $userModel->getUsersWithAdvisers();
}
?>

<?php include "./layout/sidebar.php";?>

<div class="container mx-auto mr-10 text-black">

    <h1 class="text-2xl font-bold mb-6">Users and Their Advisers</h1>

    <!-- Search Form -->
    <form method="GET" action="" class="mb-4">
        <label for="school_id" class="mr-2">Search by School ID:</label>
        <input type="text" name="school_id" id="school_id" value="<?php echo htmlspecialchars($searchSchoolId); ?>" class="px-4 py-2 border rounded-md" placeholder="Enter School ID">
        <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md">Search</button>
    </form>

    <?php 
    $currentStrand = '';
    $currentSection = '';
    ?>

    <div class="space-y-6">
        <?php if (!empty($results)) : ?>
            <?php foreach ($results as $row) : ?>
                <?php 
                // Check if we need a new strand or section header
                if ($currentStrand !== $row['user_strand'] || $currentSection !== $row['user_section']) {
                    $currentStrand = $row['user_strand'];
                    $currentSection = $row['user_section'];
                ?>
                    <div class="mt-8">
                        <h2 class="text-xl font-semibold text-gray-700">Strand: <?php echo htmlspecialchars($currentStrand); ?> - Section: <?php echo htmlspecialchars($currentSection); ?></h2>
                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full bg-white border border-gray-200 shadow-lg rounded-lg">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="text-left px-4 py-2 border">User Name</th>
                                        <th class="text-left px-4 py-2 border">User School ID</th>
                                        <th class="text-left px-4 py-2 border">Adviser School ID</th>
                                    </tr>
                                </thead>
                                <tbody class="overflow-y-auto" style="max-height: 400px;">
                <?php } ?>

                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['user_name'] . ' ' . $row['user_surname']); ?></td>
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['user_school_id']); ?></td>
                    <!-- <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['adviser_name']); ?></td> -->
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['teacher_school_id']); ?></td>
                </tr>

                <?php 
                // Close table body when the next section or strand starts
                if (next($results) === false || $currentStrand !== $results[key($results)]['user_strand'] || $currentSection !== $results[key($results)]['user_section']) {
                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-gray-500">No matching records found.</div>
        <?php endif; ?>
    </div>
</div>
