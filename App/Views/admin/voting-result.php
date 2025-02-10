<?php
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Model/Candidate.php';

// Database connection
$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);

// Handle AJAX delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $deleteQuery = "DELETE FROM candidates WHERE id = :id";
    $deleteStmt = $db->prepare($deleteQuery);
    $deleteStmt->bindParam(':id', $deleteId);
    
    if ($deleteStmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
    exit; // Stop further execution
}

// Query to fetch candidates
$candidatesQuery = "
    SELECT c.id, c.name, c.position, c.image, 
           (SELECT COUNT(*) FROM votes WHERE votes.candidate_id = c.id) AS vote_count,
           (SELECT COUNT(*) FROM votes) AS total_votes
    FROM candidates c
    ORDER BY c.id DESC";
$stmt = $db->prepare($candidatesQuery);
$stmt->execute();
?>

<?php include "./layout/sidebar.php"; ?>

<div class="bg-white mt-20 rounded-lg shadow-lg p-4 md:p-6 w-full max-w-7xl mx-auto text-black">
    <div class="w-full bg-gray-50 p-4 md:p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-bold mb-4 text-center text-gray-800">List of Candidates</h2>

        <!-- Scrollable Table -->
        <div class="overflow-y-auto max-h-[400px] scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
            <table class="min-w-full bg-white border border-gray-200 table-auto">
                <thead class="bg-gray-100 sticky top-0">
                    <tr>
                        <th class="px-4 py-3 border text-center text-sm font-medium text-gray-700">Profile</th>
                        <th class="px-4 py-3 border text-left text-sm font-medium text-gray-700">Name</th>
                        <th class="px-4 py-3 border text-left text-sm font-medium text-gray-700">Position</th>
                        <th class="px-4 py-3 border text-center text-sm font-medium text-gray-700">Votes (%)</th>
                        <th class="px-4 py-3 border text-center text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : 
                        $votePercentage = ($row['total_votes'] > 0) ? round(($row['vote_count'] * 2), 2) : 0;
                    ?>
                        <tr id="row-<?php echo $row['id']; ?>" class="hover:bg-gray-50 transition-all">
                            <td class="px-4 py-3 border text-center">
                                <img src="../../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Candidate Image" class="w-16 h-16 md:w-24 md:h-24 object-cover rounded-lg shadow-md">
                            </td>
                            <td class="px-4 py-3 border text-sm text-gray-700"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="px-4 py-3 border text-sm text-gray-700"><?php echo htmlspecialchars($row['position']); ?></td>
                            <td class="px-4 py-3 border text-center text-sm text-gray-700 font-semibold"><?php echo $votePercentage; ?>%</td>
                            <td class="px-4 py-3 border text-center">
                                <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-500 transition-all">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SweetAlert Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(candidateId) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to undo this action!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `delete_id=${candidateId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire("Deleted!", "Candidate has been deleted.", "success")
                        .then(() => {
                            document.getElementById("row-" + candidateId).remove(); // Remove row without reload
                        });
                    } else {
                        Swal.fire("Error!", "Failed to delete candidate.", "error");
                    }
                })
                .catch(() => Swal.fire("Error!", "Something went wrong.", "error"));
            }
        });
    }
</script>
