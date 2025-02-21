<?php
require_once '../../Model/Announcement.php';

$announcement = new Announcement();
$announcements = $announcement->getAnnouncementsWithReactions();
?>
<?php include "./layout/sidebar.php"; ?>

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

<div class="max-w-5xl mx-auto py-10 px-4 sm:px-6">
    <div class="bg-white rounded-xl shadow-xl p-6 sm:p-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-center text-gray-800 mb-6 sm:mb-8">📢 Announcements</h1>
        
        <!-- Scrollable Container -->
        <div class="space-y-6 overflow-y-auto max-h-[65vh] px-2 sm:px-4 scrollbar-hide">
            <?php foreach ($announcements as $announcement): ?>
                <div class="bg-gray-100 shadow-md rounded-lg p-4 sm:p-6 transition duration-300 hover:shadow-lg">
                    <!-- Announcement Details -->
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-2 sm:mb-3">
                        <?= htmlspecialchars($announcement['title']); ?>
                    </h3>
                    <p class="text-gray-700 mb-3 sm:mb-4 leading-relaxed text-sm sm:text-base">
                        <?= nl2br(htmlspecialchars($announcement['content'])); ?>
                    </p>
                    <div class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">
                        🕒 Posted on: <?= htmlspecialchars($announcement['date_created']); ?>
                    </div>

                    <!-- Reaction Buttons -->
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-lg sm:text-xl">
                        <button onclick="react(<?= $announcement['announcement_id']; ?>, 'haha')"
                            class="emoji-btn flex items-center space-x-1 text-gray-600 hover:text-yellow-500 transition">
                            <span class="text-xl sm:text-2xl">😆</span>
                            <span><?= $announcement['haha']; ?></span>
                        </button>
                        <button onclick="react(<?= $announcement['announcement_id']; ?>, 'angry')"
                            class="emoji-btn flex items-center space-x-1 text-gray-600 hover:text-red-500 transition">
                            <span class="text-xl sm:text-2xl">😡</span>
                            <span><?= $announcement['angry']; ?></span>
                        </button>
                        <button onclick="react(<?= $announcement['announcement_id']; ?>, 'love')"
                            class="emoji-btn flex items-center space-x-1 text-gray-600 hover:text-pink-500 transition">
                            <span class="text-xl sm:text-2xl">❤️</span>
                            <span><?= $announcement['love']; ?></span>
                        </button>
                        <button onclick="react(<?= $announcement['announcement_id']; ?>, 'like')"
                            class="emoji-btn flex items-center space-x-1 text-gray-600 hover:text-blue-500 transition">
                            <span class="text-xl sm:text-2xl">👍</span>
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
                // alert('Reaction saved!');
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
