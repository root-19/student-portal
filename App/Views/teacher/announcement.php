<?php
require_once '../../Model/Announcement.php';

$announcement = new Announcement();
$announcements = $announcement->getAnnouncementsWithReactions();
// var_dump($announcements);
?>
<?php include "./layout/sidebar.php"; ?>

<div class="flex flex-col items-center bg-gray-100 py-8 ml-10">
    <div class="bg-white mr-44 rounded-lg shadow-lg p-6 w-full max-w-4xl">
        <h1 class="text-4xl font-bold text-center text-black mb-6 md:text-5xl">Announcements</h1>
        
        <!-- Scrollable Container -->
        <div class="space-y-6 max-h-[70vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
            <?php foreach ($announcements as $announcement): ?>
                <div class="bg-white shadow rounded-lg p-6">
                    <!-- Announcement Details -->
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 md:text-3xl">Title: 
                        <?= htmlspecialchars($announcement['title']); ?>
                    </h3>
                    <hr>
                    <p class="text-gray-600 mb-6 md:text-lg">
                        <?= nl2br(htmlspecialchars($announcement['content'])); ?>
                    </p>
                    <div class="text-sm text-gray-500 mb-6">
                        Posted on: <?= htmlspecialchars($announcement['date_created']); ?>
                    </div>
                    <!-- Reaction Buttons at the Bottom -->
                    <div class="flex justify-start space-x-6 text-xl md:text-2xl">
                        <button onclick="react(<?= $announcement['announcement_id']; ?>, 'haha')"
                            class="emoji-btn flex items-center space-x-2 text-gray-600 hover:text-yellow-500 transition">
                            <span>😆</span>
                            <span><?= $announcement['haha']; ?></span>
                        </button>
                        <button onclick="react(<?= $announcement['announcement_id']; ?>, 'angry')"
                            class="emoji-btn flex items-center space-x-2 text-gray-600 hover:text-red-500 transition">
                            <span>😡</span>
                            <span><?= $announcement['angry']; ?></span>
                        </button>
                        <button onclick="react(<?= $announcement['announcement_id']; ?>, 'love')"
                            class="emoji-btn flex items-center space-x-2 text-gray-600 hover:text-pink-500 transition">
                            <span>❤️</span>
                            <span><?= $announcement['love']; ?></span>
                        </button>
                        <button onclick="react(<?= $announcement['announcement_id']; ?>, 'like')"
                            class="emoji-btn flex items-center space-x-2 text-gray-600 hover:text-blue-500 transition">
                            <span>👍</span>
                            <span><?= $announcement['like_count']; ?></span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<script>
    function react(announcementId, reactionType) {
        fetch('../../Model/reaction.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ announcementId, reactionType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Reaction saved!');
                location.reload();
            } else {
                alert('Failed to save reaction.');
            }
        });
    }

    // Add a subtle animation to emojis on hover
    document.querySelectorAll('.emoji-btn span:first-child').forEach(emoji => {
        emoji.addEventListener('mouseenter', () => {
            emoji.style.transform = 'scale(1.2)';
        });
        emoji.addEventListener('mouseleave', () => {
            emoji.style.transform = 'scale(1)';
        });
        emoji.addEventListener('click', () => {
            emoji.style.animation = 'bounce 0.3s';
            emoji.addEventListener('animationend', () => {
                emoji.style.animation = '';
            });
        });
    });
</script>

<style>
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
