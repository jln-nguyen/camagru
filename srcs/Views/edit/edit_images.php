<?php require __DIR__ . '/../header.php'; ?>

<div class="edit-page">
    <section class="edit-main">
        <a class="form-title">New post</a>

        <div class="stage" id="stage">
            <video id="webcam" autoplay playsinline></video>
            <img id="uploaded-preview" alt="Uploaded photo" style="display:none">
            <!-- les overlays sélectionnés sont ajoutés ici par le JS -->
        </div>
        <canvas id="canvas" width="640" height="480" hidden></canvas>

        <div class="overlays" role="group" aria-label="Overlays">
            <?php foreach ($overlays as $overlay): ?>
                <img src="<?= htmlspecialchars($overlay) ?>" class="overlay-thumb"
                     data-overlay="<?= htmlspecialchars($overlay) ?>" alt="Overlay">
            <?php endforeach; ?>
        </div>

        <div class="controls">
            <button id="capture-btn" type="button" class="btn-primary" disabled>Capture</button>

            <label for="file-upload" class="btn-outline" id="upload-btn">Upload a photo</label>
            <input type="file" id="file-upload" accept="image/png, image/jpeg">

            <button id="take-photo" type="button" class="btn-outline" style="display:none">Use camera</button>
        </div>
    </section>

    <aside class="edit-side">
        <a class="form-title">Your posts</a>
        <?php if (empty($images)): ?>
            <p class="muted">Nothing yet.</p>
        <?php else: ?>
            <div class="images-list">
                <?php foreach ($images as $image): ?>
                    <div class="image-item">
                        <img src="<?= htmlspecialchars($image['file_path']) ?>" alt="Your post" loading="lazy">
                        <form method="POST" action="/delete-image/<?= (int)$image['id'] ?>">
                            <button type="submit" class="link-btn">Delete</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </aside>
</div>

<script src="/js/edit.js"></script>
<?php require __DIR__ . '/../footer.php'; ?>
