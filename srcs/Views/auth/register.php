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

    <form method="POST" action="/register">
        <label>Username: <input type="text" name="username"></label>
        <label>Email: <input type="email" name="email"></label>
        <label>Password: <input type="password" name="password"></label>
        <button type="submit">Sign In</button>
    </form>
</div>
<?php require __DIR__ . '/../footer.php'; ?>