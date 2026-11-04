<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
<h1>Forgot Password</h1>
    <form method="post" action="/forgot-password">
        <label>Email: <input type="email" name="email" placeholder="example@example.com" required ></label>
        <button type="submit">Reset Password</button>
    </form>
    <p><a class="back-link" href="/login">← Back to Login</a></p>
</div>
<?php require __DIR__ . '/../footer.php'; ?>