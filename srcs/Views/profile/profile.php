<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
    <a class="form-title">Profile</a>
    <p>Welcome, <a class="container-bold"><?= htmlspecialchars($username) ?></a>!</p>
    <p>Email: <a class="container-bold"><?= htmlspecialchars($email) ?></a></p>
    <p>Notifications by email: <a class="container-bold"><?= $notifications_enabled ? 'Enabled' : 'Disabled' ?></a></p>
    <p><a href="/modify" class="container-link">Update Profile</a></p>
</div>
<?php require __DIR__ . '/../footer.php'; ?>