<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
    <h1>Profile</h1>
    <p>Welcome, <?= htmlspecialchars($username) ?>!</p>
    <p>Email: <?= htmlspecialchars($email) ?></p>
    <p>Notifications by email: <?= $notifications_enabled ? 'Enabled' : 'Disabled' ?></p>
    <p><a href="/modify">Update Profile</a></p>
</div>
<?php require __DIR__ . '/../footer.php'; ?>