<?php require __DIR__ . '/../header.php'; ?>
<div class="edit-page">
    <div class="edit-main">
        <h1>New Post</h1>
        <div class="webcam-container" style="position: relative; width: 400px; height: 300px;">
            <video id="webcam" autoplay playsinline width="320" height="240"></video>
            <img id="overlay-preview" style="position: absolute; top: 0; left: 0; width: 400px; height: 300px; display: none;">
            <img id="uploaded-preview" width="320" height="240" style="display: none;">
        </div>
        <canvas id="canvas" width="320" height="240" style="display: none;"></canvas>

        <div class="overlays">
            <h3>Choose an overlay</h3>
            <img src="/overlays/glasses.png" class="overlay-thumb" data-overlay="/overlays/glasses.png" alt="glasses" width="100">
            <img src="/overlays/hearts.png" class="overlay-thumb" data-overlay="/overlays/hearts.png" alt="hearts" width="100">
            <img id="preview" alt="Preview-edit" style="display: none;">
        </div>
        <label id="upload-btn" for="upload-file" class="custom-file-upload"> 📂 </label>
        <input type="file" id="upload-file" name="background" accept = "image/png, image/jpeg" />
        <button id="take-photo" style="display: none"> 📷 </button>
        <button id="capture-btn" disabled>Capture</button>
    </div>
    </div>
    <div class="edit-side">
        <h1>Your Posts</h1>
        <?php if (empty($images)): ?>
            <p>You haven't posted yet</p>
        <?php else: ?>
            <div class="images-list">
                <?php foreach ($images as $image): ?>
                    <div class="image-item">
                        <img src="<?= htmlspecialchars($image['file_path']) ?>" alt="Image" height="100" width="160">
                        <form method="POST" action="/delete-image/<?= $image['id'] ?>" class="delete-form">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<script src="/js/edit.js"></script>
<?php require __DIR__ . '/../footer.php'; ?>