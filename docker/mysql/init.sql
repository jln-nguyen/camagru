CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_confirmed BOOLEAN DEFAULT false,
    confirmation_token VARCHAR(64),
    reset_token VARCHAR(64),
    reset_token_expire_at DATETIME,
    notify_on_comment BOOLEAN DEFAULT true,
    UNIQUE (username),
    UNIQUE (email)
);