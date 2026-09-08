<?php require __DIR__ . '/../header.php'; ?>

<div class="container">
    <!-- <h1>All Creations</h1> -->
    <div class="gallery-item">
        <?php if (empty($images)): ?>
            <h2>No posts have been made yet. Be the first to post!</h2>
            <a href="/edit-images">Create a new post</a>               
        <?php else: ?>
            <a href="/edit-images">Create a new post</a>
            <a href="/gallery/<?= $image['id'] ?>">
                <img src="<?= htmlspecialchars($image['file_path']) ?>" alt="Image" height="200" width="350">
            </a>

            <div class="actions">
                <form method="POST" action="/like/<?= $image['id'] ?>" class="like-form">
                    <button type="submit" class="like-btn">
                        <?= $userLiked[$image['id']] ? '♥' : '♡' ?>
                        <?= $likesCount[$image['id']] ?>
                    </button>
                </form>

                <a href="/gallery/<?= $image['id'] ?>" class="comment-link">
                    💬 <?= $commentsCount[$image['id']] ?>
                </a>
                <p>Posted by: <?= htmlspecialchars($image['username'])?></p>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>">&laquo; Previous</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" <?= $i === $page ? 'class="active"' : '' ?>><?= $i ?></a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../footer.php'; ?>