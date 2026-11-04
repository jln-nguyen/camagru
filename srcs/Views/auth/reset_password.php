<?php require __DIR__ . '/../header.php'; ?>
<div class="auth-fom">
<a class="form-title">Reset Password</a>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<form method="post" action="/reset-password" class="tezt-area">
    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
    <label>New Password: <input type="password" name="new_password" placeholder="Your Password..." required></label>
    <label>Confirm New Password: <input type="password" name="confirm_password" placeholder="Your Password..." required></label>
    <button type="submit">Reset Password</button>
</form>
</div>