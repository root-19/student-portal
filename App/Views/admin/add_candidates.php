<?php
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Model/Candidate.php';

// Database connection
$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $image = $_FILES['image'];

    $uploadDir = '../../uploads/';
    $imagePath = $uploadDir . uniqid() . '_' . basename($image['name']);

    if (move_uploaded_file($image['tmp_name'], $imagePath)) {
        if ($candidate->addCandidate($name, $position, $imagePath)) {
            // Success message
            // echo "Candidate added successfully!";
        } else {
            // Error message
            // echo "Error adding candidate.";
        }
    } else {
        // Image upload failed
        echo "Failed to upload image.";
    }
}
?>
<?php include "./layout/sidebar.php"; ?>
    <div class="container mx-auto max-w-lg bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-black">Add Candidate</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-sm font-medium text-black">Name</label>
                <input type="text" name="name" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4 text-black">
                <label class="block text-sm font-medium text-black">Position</label>
                <select name="position" class="w-full p-2 border rounded" required>
                    <option value="Group Leader">Group Leader</option>
                    <option value="Secretary">Secretary</option>
                    <option value="Treasurer">Treasurer</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Image</label>
                <input type="file" name="image" accept="image/*" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Add Candidate
            </button>
        </form>
    </div>
</body>
</html>
