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
        <header class="site-header">
            <div class="header-inner">
                <a class="home-link" href="/">Camagru</a>
                    <div class="user-menu">
                        <button class="user-button" type="button" aria-label="Open user menu" aria-expanded="false">
                            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path d="M12 12c2.7 0 4.9-2.2 4.9-4.9S14.7 2.2 12 2.2 7.1 4.4 7.1 7.1 9.3 12 12 12zm0 2.4c-3.3 0-9.8 1.7-9.8 5v1.4h19.6V19.4c0-3.3-6.5-5-9.8-5z"/>
                            </svg>
                        </button>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <nav class="dropdown-menu" aria-hidden="true">
                                <a href="/profile">Profile</a>
                                <a href="/logout">Logout</a>
                            </nav>
                        <? else : ?>
                            <nav class="dropdown-menu" aria-hidden="true">
                                <a href="/login">Login</a>
                                <a href="/register">Register</a>
                            </nav>
                        <?php endif; ?>
                    </div>
            </div>
        </header>
        <main>




        