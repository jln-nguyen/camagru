<?php require __DIR__ . '/../header.php'; ?>

<div class="detail">
    <a href="/" class="back-link">← Back to gallery</a>

    <img class="detail-img" src="<?= htmlspecialchars($image['file_path']) ?>"
         alt="Photo by <?= htmlspecialchars($image['username']) ?>">

    <div class="detail-meta">
        <span>
            <?= htmlspecialchars($image['username']) ?>
            <span class="muted">· <?= date('M j, Y', strtotime($image['created_at'])) ?></span>
        </span>

        <form method="POST" action="/like/<?= (int)$image['image_id'] ?>">
            <button type="submit" class="icon-btn <?= $userLiked ? 'liked' : '' ?>" aria-label="Like">
                <?= $userLiked ? '♥' : '♡' ?> <?= $likesCount ?>
            </button>
        </form>
    </div>

    <section class="comments">
        <h2><?= $commentsCount ?> comment<?= $commentsCount === 1 ? '' : 's' ?></h2>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="/comment/<?= (int)$image['image_id'] ?>" class="comment-form">
                <textarea name="comment" rows="3" maxlength="500" placeholder="Add a comment…" required></textarea>
                <button type="submit" class="btn-outline">Post</button>
            </form>
        <?php else: ?>
            <p class="muted"><a href="/login">Log in</a> to like or comment.</p>
        <?php endif; ?>

        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <div class="comment-head">
                    <strong><?= htmlspecialchars($comment['username']) ?></strong>
                    <span class="muted"><?= date('M j, H:i', strtotime($comment['created_at'])) ?></span>
                </div>
                <p><?= nl2br(htmlspecialchars($comment['comment_text'])) ?></p>
            </div>
        <?php endforeach; ?>
    </section>
</div>

<?php require __DIR__ . '/../footer.php'; ?>