<?php
// Include necessary files
require_once '../../Model/Announcement.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if title and content are provided
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    // Validate the form fields
    if (empty($title) || empty($content)) {
        echo '<div class="text-center text-red-600 mt-4">Please fill in all fields.</div>';
    } else {
        try {
            // Assume the user ID is 1 (this should come from a session or authenticated user)
            $userId = 1;

            // Create an instance of the Announcement class
            $announcement = new Announcement();
            
            // Create the announcement
            $announcementId = $announcement->createAnnouncement($title, $content, $userId);

            // Success message
            echo '<div class="text-center text-green-600 mt-4">Announcement created successfully!</div>';
        } catch (Exception $e) {
            // Error message
            echo '<div class="text-center text-red-600 mt-4">Something went wrong. Please try again.</div>';
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
                <label for="title" class="block text-sm font-medium text-gray-700">Announcement Title</label>
                <input type="text" name="title" id="title" required class="mt-1 p-3 text-black block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <!-- Announcement Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Announcement Content</label>
                <textarea name="content" id="content" rows="6" required class="mt-1 p-3 text-black block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="w-full py-3 px-4 bg-indigo-600 text-white font-semibold rounded-md shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Create Announcement
                </button>
            </div>
        </form>
    </div>
</div>
