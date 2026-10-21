<?php require __DIR__ . '/../header.php'; ?>

<div class="auth-form">
<h1>Sign Up</h1>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="/register">
        <label><span>Username<sup>*</sup>:</span><input type="text" name="username" placeholder="Your Username..."></label>
        <label>Email: <input type="email" name="email" placeholder="example@example.com"></label>
        <label><span>Password<sup>**</sup>:</span><input type="password" name="password" placeholder="Your Password..."></label>
        <label>Confirm Password: <input type="password" name="confirm_password" placeholder="Confirm your passsword..."></label>
        <p class="form-note">* Must contain at least 5 characters. Allowed characters : [a-z], [A-Z], [1-9], '-', '_'.</p>
        <p class="form-note">** Must contain at least 8 characters with at least 1 digit, 1 uppercase letter, 1 lowercase letter and 1 special character.</p>
        <button type="submit">Sign Up</button>
    </form>
    <p><a href="/login">Already have an account? Login here.</a></p>
</div>
<?php require __DIR__ . '/../footer.php'; ?>