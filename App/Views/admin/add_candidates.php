<?php
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Model/Candidate.php';

// Database connection
$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $position = $_POST['position'] ?? '';

    if (!empty($name) && !empty($position) && isset($_FILES['image'])) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageFolder = "../../uploads/" . basename($imageName);

        // Move uploaded file to the destination folder
        if (move_uploaded_file($imageTmpName, $imageFolder)) {
            // Insert candidate into the database
            if ($candidate->addCandidate($name, $position, $imageName)) {
                echo "<script>alert('Candidate added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding candidate. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Error uploading image.');</script>";
        }
    } else {
        echo "<script>alert('Please fill out all fields and upload an image.');</script>";
    }
}
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
                <option value="President">President</option>
    <option value="Vice President">Vice President</option>
    <option value="Secretary">Secretary</option>
    <option value="Treasurer">Treasurer</option>
    <option value="Auditor">Auditor</option>
    <option value="Public Relations Officer">Public Relations Officer</option>
    <option value="Sergeant at Arms">Sergeant at Arms</option>
    <option value="Grade Level Representative">Grade Level Representative</option>
    <option value="Class Representative">Class Representative</option>
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
