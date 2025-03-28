<?php
// Include necessary files
require_once '../../Model/Announcement.php';
require_once "../../Database/Database.php";
require_once "../../Controller/Usermodel.php";

$db = new Database();
$conn = $db->connect();
$userModel = new UserModel($conn);
// Get the logged-in user's ID
// $userId = $_SESSION['user_id'] ?? null; 
// var_dump($userId, $_POST);
// exit();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if title and content are provided
    $title = $_POST['title'] ?? '';
    $userId = $_POST['user_id'] ??  '';
        $content = $_POST['content'] ?? '';

    // Validate the form fields
    if (empty($title) || empty($content)) {
        echo '<div class="text-center text-red-600 mt-4">Please fill in all fields.</div>';
    } else {
        try {
            // Create an instance of the Announcement class
            $announcement = new Announcement();
        
            // Create the announcement
            $announcementId = $announcement->createAnnouncement($title, $content, $userId);
        
            // echo '<div class="text-center text-green-600 mt-4">Announcement created successfully!</div>';
        } catch (Exception $e) {
            echo '<div class="text-center text-red-600 mt-4">Error: ' . $e->getMessage() . '</div>';
        }
    }   
}     
?>

<?php include "./layout/sidebar.php"; ?>
<!-- Announcement Form -->
<div class="mt-20 rounded-lg shadow-lg p-6 w-full h-15 max-w-2x">
    <div class="max-w-4xl w-full space-y-8 bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-center text-3xl font-extrabold text-gray-900">Create a New Announcement</h2>

        <form method="POST" action="" class="space-y-6">
            <!-- Announcement Title -->
            <div>
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <label for="title" class="block text-sm font-medium text-gray-700">Announcement Title</label>
                <input type="text" name="title" id="title" required class="block w-full p-4 text-gray-900 border border-gray-300 rounded-lg bg-gray-50 text-base focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </div>

            <!-- Announcement Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Announcement Content</label>
                <textarea name="content" id="content" rows="6" required class="w-full px-0 text-sm text-gray-900 bg-white border-0 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"></textarea>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full py-3 px-4 bg-green-800 text-white font-semibold rounded-md shadow-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Create Announcement
                </button>
            </div>
        </form>
    </div>
</div>