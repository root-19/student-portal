<?php
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Model/Candidate.php';

// Database connection
$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 

// Cast a vote
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that both 'id' and 'position' are passed from the form
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $position = isset($_POST['position']) ? $_POST['position'] : null;
    
    // Check if both values are available
    if ($id && $position && $candidate->vote($userId, $position, $id)) {
        // echo "<p class='text-green-500'>Vote casted successfully!</p>";
    } else {
        // echo "<p class='text-red-500'>You have already voted for this position or invalid data.</p>";
    }
}

// Fetch all candidates
$candidates = $candidate->getCandidates();
?>
<?php include "./layout/sidebar.php"; ?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-center mb-6">Candidates</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-h-[500px] overflow-y-auto">
        <?php foreach ($candidates as $candidate): ?>
            <div class="bg-white p-4 rounded-lg shadow-md text-center">
                <img src="<?= htmlspecialchars($candidate['image']); ?>" alt="Candidate Image" class="w-full h-40 object-cover rounded mb-4">
                <h3 class="text-xl font-bold"><?= htmlspecialchars($candidate['name']); ?></h3>
                <p class="text-gray-600 mb-4"><?= htmlspecialchars($candidate['position']); ?></p>

                <!-- Only allow voting if the user hasn't already voted for this position -->
                <?php
                $position = $candidate['position'];  // Get the position of the candidate

                // Check if the user has already voted for this position
                $query = "SELECT * FROM votes WHERE user_id = :user_id AND position = :position";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':position', $position);
                $stmt->execute();

                if ($stmt->rowCount() > 0): ?>
                    <p class="text-sm text-gray-500 mt-2">You have already voted for this position.</p>
                <?php else: ?>
                    <form action="" method="POST" id="vote-form-<?= $candidate['id']; ?>" onsubmit="return confirmVote(<?= $candidate['id']; ?>)">
                        <input type="hidden" name="id" value="<?= $candidate['id']; ?>">
                        <input type="hidden" name="position" value="<?= $candidate['position']; ?>"> <!-- Ensure the position is passed -->
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            Vote
                        </button>
                    </form>
                <?php endif; ?>

                <p class="text-sm text-gray-500 mt-2">Votes: <?= $candidate['votes']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Include SweetAlert2 JS and CSS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // SweetAlert Confirmation for Voting
    function confirmVote(candidateId) {
        event.preventDefault(); // Prevent the form from being submitted immediately
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to cast your vote for this candidate?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, vote!',
            cancelButtonText: 'No, cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                document.getElementById('vote-form-' + candidateId).submit();
            }
        });
    }
</script>
