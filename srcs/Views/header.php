<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Camagru</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/css/style.css">
        <script src="/js/script.js" defer></script>
    </head>
    <body>
        <?php $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
        <header class="site-header">
            <a class="logo" href="/">Camagru.</a>

            <nav class="nav" aria-label="Main">
                <a href="/" class="<?= $path === '/' ? 'active' : '' ?>">Gallery</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/edit-images" class="<?= $path === '/edit-images' ? 'active' : '' ?>">Studio</a>
                <?php endif; ?>
            </nav>

            <div class="header-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/profile">Profile</a>
                    <a href="/logout" class="btn-outline">Log out</a>
                <?php else: ?>
                    <a href="/login">Log in</a>
                    <a href="/register" class="btn-outline">Sign up</a>
                <?php endif; ?>
            </div>
        </header>
        <main>




        