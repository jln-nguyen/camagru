<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
    <div class="post-details">
        <h2>Post by <?= htmlspecialchars($image['username']) ?></h2>
        <p><?= htmlspecialchars($image['created_at']) ?></p>
        <img src="<?= htmlspecialchars($image['file_path']) ?>" alt="Image" height="480" width="720">
        <div class="actions">
            <form method="POST" action="/like/<?= $image['image_id'] ?>" class="like-form">
                <button type="submit" class="like-btn">
                    <?= $userLiked ? '♥' : '♡' ?>
                    <?= $likesCount ?>
                </button>
            </form>
            <a class="comment-link">
                💬 <?= $commentsCount?>
            </a>
        </div>
        <h2>Comments</h2>
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><strong><?= htmlspecialchars($comment['username']) ?>:</strong> <?= htmlspecialchars($comment['comment_text']) ?></p>
                    <p><small><?= htmlspecialchars($comment['created_at']) ?></small></p>
                </div>
            <?php endforeach; ?>
            <form method="POST" action="/comment/<?= $image['image_id'] ?>" class="comment-form">
                <input type="hidden" name="image_id" value="<?= $image['image_id'] ?>">
                <textarea name="comment" required></textarea>
                <button type="submit">Add Comment</button>
            </form>
    </div>
    <a href="/">Back to Gallery</a>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
