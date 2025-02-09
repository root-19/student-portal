<?php
require_once __DIR__ . '/../../Database/Database.php';
require_once __DIR__ . '/../../Model/Candidate.php';

$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $position = isset($_POST['position']) ? $_POST['position'] : null;

    if ($id && $position && $candidate->vote($userId, $position, $id)) {
        echo "<script>
                Swal.fire('Success', 'Your vote has been casted!', 'success').then(() => {
                    location.reload();
                });
              </script>";
    }
}

$candidates = $candidate->getCandidates();
?>

<?php include "./layout/sidebar.php"; ?>
<style>
/* Hide scrollbar but keep scrolling functionality */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;  /* Hide scrollbar for IE and Edge */
    scrollbar-width: none;  /* Hide scrollbar for Firefox */
}
</style>

<div class="max-w-6xl mx-auto py-8 px-4 mr-15 text-black overflow-y-auto max-h-[80vh] scrollbar-hide ">
    <h1 class="text-4xl font-bold text-center mb-8">Candidates</h1>
    
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($candidates as $candidate): ?>
            <div class="bg-white p-6 rounded-lg shadow-lg text-center hover:shadow-xl transition duration-300">
                <img src="<?= htmlspecialchars($candidate['image']); ?>" alt="Candidate Image" class="w-full h-48 object-cover rounded-lg mb-4">
                <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($candidate['name']); ?></h3>
                <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($candidate['position']); ?></p>

                <?php
                $position = $candidate['position'];
                $query = "SELECT * FROM votes WHERE user_id = :user_id AND position = :position";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':user_id', $userId);
                $stmt->bindParam(':position', $position);
                $stmt->execute();
                ?>

                <?php if ($stmt->rowCount() > 0): ?>
                    <button class="bg-green-300 text-gray-500 px-4 py-2 rounded cursor-not-allowed">
                        Voted
                    </button>
                <?php else: ?>
                    <form action="" method="POST" id="vote-form-<?= $candidate['id']; ?>" onsubmit="return confirmVote(<?= $candidate['id']; ?>)">
                        <input type="hidden" name="id" value="<?= $candidate['id']; ?>">
                        <input type="hidden" name="position" value="<?= $candidate['position']; ?>">
                        <button type="submit" class="bg-green-800 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                            Vote
                        </button>
                    </form>
                <?php endif; ?>

                <p class="text-sm text-green-800 mt-3">Votes: <?= $candidate['votes']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmVote(candidateId) {
        event.preventDefault();
        
        Swal.fire({
            title: 'Confirm Vote',
            text: 'Are you sure you want to vote for this candidate?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, vote!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('vote-form-' + candidateId).submit();
            }
        });
    }
</script>
