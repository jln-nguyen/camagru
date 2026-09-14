<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
    <h1>Image Details</h1>
    <img src="<?= htmlspecialchars($image['file_path']) ?>" alt="Image" height="400" width="700">
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
        <p>Posted by: <?= htmlspecialchars($image['username']) ?> - <?= htmlspecialchars($image['created_at']) ?></p>
    </div>
    <h2>Comments</h2>
        <form method="POST" action="/comment/<?= $image['image_id'] ?>" class="comment-form">
            <input type="hidden" name="image_id" value="<?= $image['image_id'] ?>">
            <textarea name="comment" required></textarea>
            <button type="submit">Add Comment</button>
        </form>
    <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <p><strong><?= htmlspecialchars($comment['username']) ?>:</strong> <?= htmlspecialchars($comment['comment_text']) ?></p>
            <p><small><?= htmlspecialchars($comment['created_at']) ?></small></p>
        </div>
    <?php endforeach; ?>
    <a href="/">Back to Gallery</a>
</div>
<?php require __DIR__ . '/../footer.php'; ?>
