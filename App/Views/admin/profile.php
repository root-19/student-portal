<?php

session_start();

require_once '../../Model/studentProfile.php';
require_once '../../Database/Database.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$userId = (int) $_SESSION['user_id'];

$profileModel = new studentProfile();
$db = (new Database())->connect();
$user = $profileModel->getUserById($userId);

if (!$user) {
    die("User not found.");
}
?>

<?php include "./layout/sidebar.php"; ?>
<div class="bg-white   mt-20 rounded-lg shadow-lg p-6 w-full h-15 max-w-2xl">
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Profile</h1>

<div id="profileView" class="space-y-4">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-amber-500 text-black rounded-full flex items-center justify-center font-bold">
                    <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($user['name'] ?? 'N/A') ?></h2>
                    <p class="text-sm text-gray-600"><?= htmlspecialchars($user['email'] ?? 'N/A') ?></p>
                </div>
            </div>
            <hr class="border-gray-200 my-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-black">
            <div><strong>Middle Initial:</strong> <?= htmlspecialchars($user['middle_initial'] ?? 'N/A') ?></div>
            <div><strong>Surname:</strong> <?= htmlspecialchars($user['surname'] ?? 'N/A') ?></div>
            <div><strong>Gender:</strong> <?= htmlspecialchars($user['gender'] ?? 'N/A') ?></div>
            <div><strong>Scholar:</strong> <?= htmlspecialchars($user['scholar'] ?? 'N/A') ?></div>
            <div><strong>LRN Number:</strong> <?= htmlspecialchars($user['lrn_number'] ?? 'N/A') ?></div>
            <div><strong>School ID:</strong> <?= htmlspecialchars($user['school_id'] ?? 'N/A') ?></div>
            <div><strong>Date of Birth:</strong> <?= htmlspecialchars($user['date_of_birth'] ?? 'N/A') ?></div>
            <div><strong>Grade:</strong> <?= htmlspecialchars($user['grade'] ?? 'N/A') ?></div>
            <div><strong>Section:</strong> <?= htmlspecialchars($user['section'] ?? 'N/A') ?></div>
            <div><strong>Strand:</strong> <?= htmlspecialchars($user['strand'] ?? 'N/A') ?></div>
            <div><strong>Phone Number:</strong> <?= htmlspecialchars($user['phone_number'] ?? 'N/A') ?></div>
            <div><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'N/A') ?></div>
            </div>
