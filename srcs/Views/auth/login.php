<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
<h1>Sign In</h1>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="post" action="/login">
        <label>Username: <input type="text" name="username"></label>
        <label>Password: <input type="password" name="password"></label>
        <p><a href="/forgot-password">Forgot your password?</a></p>
        <button type="submit">Login</button>
    </form>
    <p><a href="/register">Don't have an account? Register here.</a></p>
</div>
<?php require __DIR__ . '/../footer.php'; ?>