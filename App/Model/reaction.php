<?php
// require_once '../Database/Database.php';
require_once 'Announcement.php';

session_start(); // Start the session to access user data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $announcementId = $data['announcementId'];
    $reactionType = $data['reactionType'];

    // Ensure user is logged in and their ID is available
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        try {
            $announcement = new Announcement();
            $announcement->reactToAnnouncement($announcementId, $userId, $reactionType);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        // User not logged in
        echo json_encode(['success' => false, 'error' => 'User not logged in.']);
    }
}
