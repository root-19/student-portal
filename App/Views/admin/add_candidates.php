<?php
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Model/Candidate.php';

// Database connection
$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);
?>

<?php include "./layout/sidebar.php"; ?>

<div class="bg-white mt-20 rounded-lg shadow-lg p-6 w-full max-w-lg mx-auto text-black">
    <div class="bg-gray-50 p-6 rounded-lg shadow-md border border-gray-200">
        <h1 class="text-2xl font-semibold mb-6 text-center text-gray-800">Add Candidate</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" name="name" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                <select name="position" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                    <option value="Group Leader">Group Leader</option>
                    <option value="Secretary">Secretary</option>
                    <option value="Treasurer">Treasurer</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image</label>
                <input type="file" name="image" accept="image/*" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
            </div>
            <button type="submit" class="w-full bg-green-700 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-all">
                Add Candidate
            </button>
        </form>
    </div>
</div>
