<?php
require_once '../../Database/Database.php';
session_start();

// Ensure user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("<p class='text-red-500'>Access Denied. Admins only.</p>");
}

$database = new Database();
$db = $database->connect();

// Fetch all enrollment records
$query = "SELECT * FROM enrollments ORDER BY enrollment_status ASC";
$stmt = $db->prepare($query);
$stmt->execute();

?>

<?php include_once "./layout/sidebar.php"; ?>
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg text-black">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Enrollments</h1>

    <?php if ($stmt->rowCount() > 0): ?>
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border-b">User ID</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Fee</th>
                    <th class="py-2 px-4 border-b">Scholar Status</th>
                    <th class="py-2 px-4 border-b">Enrollment Status</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr class="hover:bg-gray-100">
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['user_id']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['name']) ?> <?= htmlspecialchars($row['surname']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['fee']) ?></td>
                    <td class="py-2 px-4 border-b"><?= $row['scholar_status'] ? 'Yes' : 'No' ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['enrollment_status']) ?></td>
                    <td class="py-2 px-4 border-b">
                        <!-- Actions (Approve, Reject) -->
                        <?php if ($row['enrollment_status'] == 'pending'): ?>
                            <form method="POST" action="update_enrollment.php">
                                <input type="hidden" name="enrollment_id" value="<?= htmlspecialchars($row['id']) ?>">
                                <button type="submit" name="approve" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Approve</button>
                                <!-- <button type="submit" name="reject" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Reject</button> -->
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-red-500">No enrollments found.</p>
    <?php endif; ?>
</div>
