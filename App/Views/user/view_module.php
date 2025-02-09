<?php
require_once __DIR__ . '/../../Model/Module.php';
require_once __DIR__ . '/../../Database/Database.php';

// Create database connection
$database = new Database();
$db = $database->connect();

// Fetch the module by ID
$moduleId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$module = new Module($db);
$moduleData = $module->getModuleById($moduleId);

if (!$moduleData) {
    echo "Module not found.";
    exit;
}

$images = json_decode($moduleData['images']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Images</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header with Back Button -->
    <header class="bg-green-800 text-white py-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <button 
                onclick="history.back()" 
                class="text-white bg-green-800 hover:bg-green-700 px-4 py-2 rounded-md font-bold">
                ← Back
            </button>
            <h1 class="text-xl font-bold">View Module: <?= htmlspecialchars($moduleData['title']); ?></h1>
        </div>
    </header>

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 overflow-y-auto h-[80vh] p-4 bg-white shadow-md rounded-lg">
            <?php foreach ($images as $image): ?>
                <div class="relative">
                    <img 
                        src="../../<?= htmlspecialchars($image); ?>" 
                        alt="Image" 
                        class="w-full h-60 object-cover rounded cursor-pointer" 
                        onclick="showModal('../../<?= htmlspecialchars($image); ?>')"
                    >
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden">
        <div class="relative">
            <button 
                class="absolute top-2 right-2 text-white text-3xl font-bold" 
                onclick="closeModal()">×</button>
            <img id="modalImage" src="" alt="Image" class="max-w-full max-h-screen rounded">
        </div>
    </div>

    <script>
        // Show the modal with the selected image
        function showModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
        }

        // Close the modal
        function closeModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
        }
    </script>
</body>
</html>
