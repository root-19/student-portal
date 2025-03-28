<?php
require_once '../../Database/Database.php';
session_start();

// Ensure user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("<p class='text-red-500'>Access Denied. Admins only.</p>");
}

$database = new Database();
$db = $database->connect();

// Query for pending enrollments
$pendingQuery = "SELECT 
                    e.id AS enrollment_id,
                    e.user_id,
                    e.name,
                    e.surname,
                    u.scholar,
                    e.enrollment_status,
                    e.fee AS amount,
                    u.grade,
                    u.semester
                  FROM enrollments e
                  JOIN users u ON e.user_id = u.id
                  WHERE e.enrollment_status = 'pending'
                  ORDER BY e.created_at DESC"; 

$pendingStmt = $db->prepare($pendingQuery);
$pendingStmt->execute();

// Query for accepted enrollments
$acceptedQuery = "SELECT 
                    e.id AS enrollment_id,
                    e.user_id,
                    e.name,
                    e.surname,
                    u.scholar,
                    e.enrollment_status,
                    e.fee AS amount,
                    u.grade,
                    u.semester
                  FROM enrollments e
                  JOIN users u ON e.user_id = u.id
                  WHERE e.enrollment_status = 'approved'
                  ORDER BY e.created_at DESC"; 

$acceptedStmt = $db->prepare($acceptedQuery);
$acceptedStmt->execute();
?>

<?php include_once "./layout/sidebar.php"; ?>

<!-- Pending Enrollments Table -->
<!-- Enrollment Requests Table -->
<div class="bg-white mt-8 rounded-lg shadow-lg p-6 w-full max-w-4xl mx-auto text-black">
  <h1 class="text-3xl font-bold mb-6 text-center">Enrollment Requests</h1>
  <div class="overflow-x-auto rounded-lg shadow-md">
    <table class="min-w-full bg-white border border-gray-200">
      <thead class="bg-gray-200 text-gray-800">
        <tr>
          <th class="px-4 py-2 border text-left">Student Name</th>
          <th class="px-4 py-2 border text-left">Grade</th>
          <th class="px-4 py-2 border text-left">Semester</th>
          <th class="px-4 py-2 border text-left">Scholar Status</th>
          <th class="px-4 py-2 border text-left">Amount</th>
          <th class="px-4 py-2 border text-left">Enrollment Status</th>
          <th class="px-4 py-2 border text-left">Action</th>
        </tr>
      </thead>
      <tbody class="text-sm">
        <?php while ($row = $pendingStmt->fetch(PDO::FETCH_ASSOC)) : ?>
          <tr class="hover:bg-gray-100">
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['name'] . ' ' . $row['surname']); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['grade']); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['semester']); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['scholar']); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo "₱" . number_format($row['amount'], 2); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['enrollment_status']); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php if ($row['enrollment_status'] === 'pending') : ?>
                <div class="flex space-x-2">
                  <form action="../../Model/update_enrollment.php" method="POST">
                    <input type="hidden" name="enrollment_id" value="<?php echo $row['enrollment_id']; ?>">
                    <input type="hidden" name="action" value="accept">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                      Accept
                    </button>
                  </form>
                </div>
              <?php else: ?>
                <span class="text-gray-600"><?php echo htmlspecialchars($row['enrollment_status']); ?></span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Accepted Enrollments Table -->
<!-- <div class="bg-white mt-8 rounded-lg shadow-lg p-6 w-full max-w-4xl mx-auto text-black">
  <h1 class="text-3xl font-bold mb-6 text-center">Accepted Enrollments</h1>
  <div class="overflow-x-auto rounded-lg shadow-md">
    <table class="min-w-full bg-white border border-gray-200">
      <thead class="bg-gray-200 text-gray-800">
        <tr>
          <th class="px-4 py-2 border text-left">Student Name</th>
          <th class="px-4 py-2 border text-left">Grade</th>
          <th class="px-4 py-2 border text-left">Semester</th>
          <th class="px-4 py-2 border text-left">Scholar Status</th>
          <th class="px-4 py-2 border text-left">Amount</th>
          <th class="px-4 py-2 border text-left">Enrollment Status</th>
        </tr>
      </thead>
      <tbody class="text-sm">
        <?php while ($row = $acceptedStmt->fetch(PDO::FETCH_ASSOC)) : ?>
          <tr class="hover:bg-gray-100">
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['name'] . ' ' . $row['surname']); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['grade']); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['semester']); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['scholar']); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo "₱" . number_format($row['amount'], 2); ?>
            </td>
            <td class="px-4 py-2 border">
              <?php echo htmlspecialchars($row['enrollment_status']); ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div> -->
