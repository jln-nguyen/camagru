<?php require __DIR__ . '/../header.php'; ?>
<div class="edit-page">
    <div class="edit-main">
        <h1>New Post</h1>
    </div>
    <div class="edit-side">
        <h2>Your Posts</h2>
        <?php if (empty($images)): ?>
            <p>You haven't posted yet</p>
        <?php else: ?>
            <?php foreach ($images as $image): ?>
                <div class="image-item">
                    <img src="<?= htmlspecialchars($image['file_path']) ?>" alt="Image" height="100" width="200">
                    <form method="POST" action="/delete-image/<?= $image['id'] ?>" class="delete-form">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/../footer.php'; ?>