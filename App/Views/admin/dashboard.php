<?php
require_once '../../Database/Database.php'; 
require_once '../../Controller/UserModel.php';

$database = new Database();
$db = $database->connect(); 
$userModel = new UserModel($db);

// Fetch total users, teachers, announcements, and candidates
$userCountQuery = "SELECT COUNT(*) AS total_users FROM users WHERE role = 'user'";
$teacherCountQuery = "SELECT COUNT(*) AS total_teachers FROM users WHERE role = 'teacher'";
$announcementCountQuery = "SELECT COUNT(*) AS total_announcements FROM announcements";
$candidateCountQuery = "SELECT COUNT(*) AS total_candidates FROM candidates";

$userCountStmt = $db->prepare($userCountQuery);
$userCountStmt->execute();
$userCount = $userCountStmt->fetch(PDO::FETCH_ASSOC)['total_users'];

$teacherCountStmt = $db->prepare($teacherCountQuery);
$teacherCountStmt->execute();
$teacherCount = $teacherCountStmt->fetch(PDO::FETCH_ASSOC)['total_teachers'];

$announcementCountStmt = $db->prepare($announcementCountQuery);
$announcementCountStmt->execute();
$announcementCount = $announcementCountStmt->fetch(PDO::FETCH_ASSOC)['total_announcements'];

$candidateCountStmt = $db->prepare($candidateCountQuery);
$candidateCountStmt->execute();
$candidateCount = $candidateCountStmt->fetch(PDO::FETCH_ASSOC)['total_candidates'];

// Fetch announcement reactions
$reactionsQuery = "
    SELECT 
        a.title,
        COALESCE(SUM(r.reaction_type = 'haha'), 0) AS haha,
        COALESCE(SUM(r.reaction_type = 'angry'), 0) AS angry,
        COALESCE(SUM(r.reaction_type = 'love'), 0) AS love,
        COALESCE(SUM(r.reaction_type = 'like'), 0) AS like_count
    FROM announcements a
    LEFT JOIN reactions r ON a.announcement_id = r.announcement_id
    GROUP BY a.announcement_id
    ORDER BY a.date_created DESC";

$reactionsStmt = $db->prepare($reactionsQuery);
$reactionsStmt->execute();
$reactionsData = $reactionsStmt->fetchAll(PDO::FETCH_ASSOC);

// Convert data to JSON for JavaScript
$reactionLabels = [];
$hahaData = [];
$angryData = [];
$loveData = [];
$likeData = [];

foreach ($reactionsData as $row) {
    $reactionLabels[] = $row['title'];
    $hahaData[] = $row['haha'];
    $angryData[] = $row['angry'];
    $loveData[] = $row['love'];
    $likeData[] = $row['like_count'];
}

?>

<?php include "./layout/sidebar.php";?>
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
</style>
<div class="container mx-auto p-6 text-black ">
    <h1 class="text-2xl font-bold mb-6 ">Dashboard</h1>

    <!-- Responsive Boxes for Counts -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 overflow-y-auto max-h-[65vh] scrollbar-hide">
        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md flex flex-col items-center">
            <h2 class="text-xl font-semibold">Total Users</h2>
            <p class="text-3xl font-bold mt-2"><?php echo $userCount; ?></p>
        </div>

        <div class="bg-green-500 text-white p-6 rounded-lg shadow-md flex flex-col items-center">
            <h2 class="text-xl font-semibold">Total Teachers</h2>
            <p class="text-3xl font-bold mt-2"><?php echo $teacherCount; ?></p>
        </div>

        <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md flex flex-col items-center">
            <h2 class="text-xl font-semibold">Total Announcements</h2>
            <p class="text-3xl font-bold mt-2"><?php echo $announcementCount; ?></p>
        </div>

        <div class="bg-yellow-500cd  text-white p-6 rounded-lg shadow-md flex flex-col items-center">
            <h2 class="text-xl font-semibold">Total Candidates</h2>
            <p class="text-3xl font-bold mt-2"><?php echo $candidateCount; ?></p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white p-6 mt-6 shadow-md rounded-lg overflow-y-auto max-h-[65vh] scrollbar-hide">
        <h2 class="text-xl font-bold mb-4">Announcement Reactions</h2>
        <canvas id="reactionChart"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get reaction data from PHP
    const reactionLabels = <?php echo json_encode($reactionLabels); ?>;
    const hahaData = <?php echo json_encode($hahaData); ?>;
    const angryData = <?php echo json_encode($angryData); ?>;
    const loveData = <?php echo json_encode($loveData); ?>;
    const likeData = <?php echo json_encode($likeData); ?>;

    // Chart Configuration
    const ctx = document.getElementById('reactionChart').getContext('2d');
    const reactionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: reactionLabels,
            datasets: [
                {
                    label: 'Haha',
                    data: hahaData,
                    backgroundColor: 'rgba(255, 193, 7, 0.6)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Angry',
                    data: angryData,
                    backgroundColor: 'rgba(220, 53, 69, 0.6)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Love',
                    data: loveData,
                    backgroundColor: 'rgba(255, 0, 128, 0.6)',
                    borderColor: 'rgba(255, 0, 128, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Like',
                    data: likeData,
                    backgroundColor: 'rgba(0, 123, 255, 0.6)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
