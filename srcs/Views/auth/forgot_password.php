<?php require __DIR__ . '/../header.php'; ?>
<div class="container">
<h1>Forgot Password</h1>
    <form method="post" action="/forgot-password">
        <label>Email: <input type="email" name="email" required></label>
        <button type="submit">Reset Password</button>
    </form>
    <p><a href="/login">Back to Login</a></p>
</div>
<?php require __DIR__ . '/../footer.php'; ?>