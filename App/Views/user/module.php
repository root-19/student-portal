<?php
include "./layout/sidebar.php";
require_once __DIR__ . '/../../Model/Module.php';
require_once __DIR__ . '/../../Database/Database.php';
// require_once "../../Controller/Middleware.php";
// Middleware::auth('admin');
// Fetch the modules
$database = new Database();
$db = $database->connect();

$module = new Module($db);
$modules = $module->getAllModules(); // Fetch all modules

if (!$modules) {
    $modules = []; // Ensure $modules is an empty array if no data is fetched
}
?>

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-center mb-6">Modules</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($modules as $module): ?>
            <?php 
            // Decode the images JSON to get the first image
            $images = json_decode($module['images'], true);
            $image = $images ? $images[0] : 'default.jpg';
            ?>
            <div class="bg-white p-4 rounded-lg shadow-md text-center">
                <img 
                    src="../../<?= htmlspecialchars($image); ?>" 
                    alt="Module Image" 
                    class="w-full h-40 object-cover rounded mb-4"
                >
                <h3 class="text-xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($module['title']); ?></h3>
                <form action="view_module.php" method="GET">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($module['id']); ?>">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        View Images
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

