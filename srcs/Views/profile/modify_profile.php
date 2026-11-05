<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
    <a class="form-title">Modify Profile</a>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" action="/modify" class="text-area">
        <label>Username: <input type="text" name="username" value="<?= htmlspecialchars($username) ?>"></label>
        <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"></label>
        <div class="notif-email">
            <p>Notifications by email:</p>
            <input type="radio" id="yes" name="notify_on_comment" value="1" <?= $notifications_enabled ? 'checked' : '' ?>>
            <label class="notif-radio"for="yes">Yes</label>
            <input type="radio" id="no" name="notify_on_comment" value="0" <?= $notifications_enabled ? '' : 'checked' ?>>
            <label class="notif-radio" for="no">No</label>
        </div>
        <input type="hidden" name="change_password" id="change-password" value="0">
        <button type="button" id="change-password-btn">Change Password</button>
        <div id="password-field" class="password-field">
            <label>New Password: <input type="password" name="password" placeholder="Your New Password..."></label>
        </div>
        <button type="submit">Update Profile</button>
    </form>
    <p><a class="back-link" href="/profile">← Back to Profile</a></p>
</div>
<?php require __DIR__ . '/../footer.php'; ?>