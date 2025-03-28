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

<div class="container mx-auto text-black p-6 overflow-y-auto max-h-[80vh] px-2 sm:px-4 scrollbar-hide">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">students</h1>
        <button onclick="openSearchModal()" class="bg-green-800 ml-10 text-white px-4 py-2 rounded-md shadow-md hover:bg-green-700">
            Search by School ID
        </button>
    </div>

    <!-- Search Modal -->
    <div id="searchModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-lg font-bold mb-4">Search by School ID</h2>
            <form method="GET" action="">
                <input type="text" name="school_id" id="school_id" class="w-full px-4 py-2 border rounded-md" placeholder="Enter School ID">
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="submit" class="px-4 py-2 bg-green-800 text-white rounded-md">Search</button>
                    <button type="button" onclick="closeSearchModal()" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-6 space-y-6">
        <?php if (!empty($results)) : ?>
            <?php 
            $currentStrand = '';
            $currentSection = '';
            foreach ($results as $row) : 
                if ($currentStrand !== $row['user_strand'] || $currentSection !== $row['user_section']) {
                    $currentStrand = $row['user_strand'];
                    $currentSection = $row['user_section'];
            ?>
                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-gray-700 bg-gray-100 p-3 rounded-md shadow">
                        Strand: <?php echo htmlspecialchars($currentStrand); ?> - Section: <?php echo htmlspecialchars($currentSection); ?>
                    </h2>
                    <div class="overflow-x-auto mt-4 border rounded-lg shadow">
                        <table class="min-w-full bg-white border">
                            <thead class="bg-gray-200 text-gray-700">
                                <tr>
                                    <th class="text-left px-4 py-2 border">User Name</th>
                                    <th class="text-left px-4 py-2 border">User School ID</th>
                                    <th class="text-left px-4 py-2 border">Adviser School ID</th>
                                </tr>
                            </thead>
                            <tbody>
            <?php } ?>

                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['user_name'] . ' ' . $row['user_surname']); ?></td>
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['user_school_id']); ?></td>
                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['teacher_school_id']); ?></td>
                </tr>

            <?php 
                if (next($results) === false || $currentStrand !== $results[key($results)]['user_strand'] || $currentSection !== $results[key($results)]['user_section']) {
            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-gray-500 mt-6 text-center">No matching records found.</div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript for Modal -->
<script>
    function openSearchModal() {
        document.getElementById("searchModal").classList.remove("hidden");
    }

    function closeSearchModal() {
        document.getElementById("searchModal").classList.add("hidden");
    }
</script>
