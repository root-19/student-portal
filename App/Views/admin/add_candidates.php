<?php
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Model/Candidate.php';

// Database connection
$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);

// Query to fetch candidates
$candidatesQuery = "SELECT name, position, image FROM candidates ORDER BY id DESC"; 
$stmt = $db->prepare($candidatesQuery);
$stmt->execute();
?>

<?php include "./layout/sidebar.php"; ?>

<div class="bg-white  mt-20 rounded-lg shadow-lg p-2 md:p-4 w-full max-w-7xl mx-auto text-black">
<!-- <div class="mt-20 rounded-lg shadow-lg p-6 w-full h-15 max-w-2x max-w-7xl mx-auto text-black"> -->
    <div class="flex flex-col md:flex-row gap-6">

        <!-- Add Candidate Form -->
        <div class="w-full md:w-1/3 bg-gray-50 p-4 md:p-6 rounded-lg shadow-sm">
            <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Add Candidate</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                    <select name="position" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="Group Leader">Group Leader</option>
                        <option value="Secretary">Secretary</option>
                        <option value="Treasurer">Treasurer</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <button type="submit" class="w-full bg-green-800 text-white px-6 py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                    Add Candidate
                </button>
            </form>
        </div>

        <!-- Candidate List -->
        <div class="w-full md:w-2/3 bg-gray-50 p-4 md:p-6 rounded-lg shadow-sm">
            <h2 class="text-xl font-bold mb-4 text-center text-gray-800">List of Candidates</h2>
            
            <!-- Table Container with Max Height for 4 Rows -->
            <div class="overflow-y-auto max-h-[300px] scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                <table class="min-w-full bg-white border border-gray-200 table-auto">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 border text-left text-sm font-medium text-gray-700">Name</th>
                            <th class="px-4 py-3 border text-left text-sm font-medium text-gray-700">Position</th>
                            <th class="px-4 py-3 border text-left text-sm font-medium text-gray-700">Profile</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr class="hover:bg-gray-50 transition-all">
                                <td class="px-4 py-3 border text-sm text-gray-700"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="px-4 py-3 border text-sm text-gray-700"><?php echo htmlspecialchars($row['position']); ?></td>
                                <td class="px-4 py-3 border">
                                    <img src="../../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Candidate Image" class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-lg">
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<style>
    /* Hide scrollbar on mobile, show on desktop */
    @media (max-width: 768px) {
        body {
            overflow-y: auto;
        }
    }
</style>


