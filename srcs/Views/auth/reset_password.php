<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
<h1>Reset Password</h1>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<form method="post" action="/reset-password">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <label>New Password: <input type="password" name="new_password" required></label>
    <label>Confirm New Password: <input type="password" name="confirm_password" required></label>
    <button type="submit">Reset Password</button>
</form>
</div>