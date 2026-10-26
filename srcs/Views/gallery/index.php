<?php require __DIR__ . '/../header.php'; ?>

<main class="gallery">
    <div class="gallery-grid">
        <?php if (empty($images)): ?>
            <p class="gallery-empty">No photos yet.</p>
        <?php else: ?>
            <?php foreach ($images as $image): ?>
                <?php $id = $image['image_id']; ?>
                <article class="card">
                    <a href="/gallery/<?= $id ?>">
                        <img src="<?= htmlspecialchars($image['file_path']) ?>" alt="Photo by <?= htmlspecialchars($image['username']) ?>" loading="lazy">
                    </a>
                    <div class="card-meta">
                        <span class="card-author">
                            <?= htmlspecialchars($image['username']) ?>
                            <span class="card-date">· <?= date('M j', strtotime($image['created_at'])) ?></span>
                        </span>
                        <div class="actions">
                            <form method="POST" action="/like/<?= $id ?>">
                                <button type="submit" class="icon-btn <?= $userLiked[$id] ? 'liked' : '' ?>" aria-label="Like">
                                    <?= $userLiked[$id] ? '♥' : '♡' ?> <?= $likesCount[$id] ?>
                                </button>
                            </form>
                            <a href="/gallery/<?= $id ?>" class="icon-btn">💬 <?= $commentsCount[$id] ?></a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <nav class="pagination" aria-label="Pages">
        <?php if ($page > 1): ?><a href="?page=<?= $page - 1 ?>">Previous</a><?php endif; ?>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($page < $totalPages): ?><a href="?page=<?= $page + 1 ?>">Next</a><?php endif; ?>
    </nav>
</main>

<?php require __DIR__ . '/../footer.php'; ?>