<?php require __DIR__ . '/../header.php'; ?>
<div class="edit-page">
    <div class="edit-main">
        <h1>New Post</h1>
        <div class="webcam-container" style="position: relative; width: 400px; height: 300px;">
            <video id="webcam" autoplay playsinline width="400" height="300"></video>
            <img id="overlay-preview" style="position: absolute; top: 0; left: 0; width: 400px; height: 300px; display: none;">
        </div>
        <canvas id="canvas" width="400" height="300" style="display: none;"></canvas>

        <div class="overlays">
            <h3>Choose an overlay</h3>
            <img src="/overlays/glasses.png" class="overlay-thumb" data-overlay="/overlays/glasses.png" alt="glasses">
            <img src="/overlays/hearts.png" class="overlay-thumb" data-overlay="/overlays/hearts.png" alt="hearts">
            <img id="preview" alt="Aperçu du montage" style="display: none;">
        </div>

        <button id="capture-btn" disabled>Capture</button>
    </div>
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
<script src="/js/edit.js"></script>
<?php require __DIR__ . '/../footer.php'; ?>