<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
    <h1>Modify Profile</h1>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" action="/modify">
        <label>Username: <input type="text" name="username" value="<?= htmlspecialchars($username) ?>"></label>
        <label>Email: <input type="email" name="email" value="<?= htmlspecialchars($email) ?>"></label>
        <label>Notifications by email: <input type="checkbox" name="notify_on_comment" value="1" <?= $notifications_enabled ? 'checked' : '' ?>></label>
        <input type="hidden" name="change_password" id="change-password" value="0">
        <button type="button" id="change-password-btn">Change Password</button>
        <div id="password-field" class="password-field">
            <label>New Password: <input type="password" name="password"></label>
        </div>
        <button type="submit">Update Profile</button>
    </form>
    <p><a href="/profile">Back to Profile</a></p>
</div>
<?php require __DIR__ . '/../footer.php'; ?>