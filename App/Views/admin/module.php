<?php include "./layout/sidebar.php"; ?>

<?php
require_once __DIR__  . '/../../Model/Module.php';
require_once __DIR__  . '/../../Database/Database.php';

// Create database connection
$database = new Database();
$db = $database->connect();

// Fetch modules
$module = new Module($db);
$modules = $module->getModules();
?>

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-center mb-6">Send Module with Images</h1>

    <!-- Form to Upload Module -->
    <form action="../../Model/uplaod.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 font-bold mb-2">Module Title:</label>
            <input type="text" id="title" name="title" class="w-full px-4 py-2 border rounded-md focus:outline-none" required>
        </div>

        <div class="mb-4">
            <label for="images" class="block text-gray-700 font-bold mb-2">Upload Images (Max 80):</label>
            <input type="file" id="images" name="images[]" class="w-full border rounded-md focus:outline-none" multiple accept="image/*" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            Submit
        </button>
    </form>

    <!-- Display Submitted Modules -->
    <h2 class="text-2xl font-bold text-center mt-12 mb-6">Submitted Modules</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($modules as $module): ?>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($module['title']); ?></h3>
                <p class="text-gray-600 mb-4 text-sm">Uploaded on: <?= htmlspecialchars($module['created_at']); ?></p>
                <div class="grid grid-cols-2 gap-2">
                    <?php foreach (json_decode($module['images']) as $image): ?>
                        <img src="../../uploads/<?= htmlspecialchars($image); ?>" alt="Image" class="w-full h-24 object-cover rounded">
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
