<?php

class User
{
    public static function usernameExists(string $username): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'SELECT * FROM users WHERE username = :username';
        $checkUsername = $pdo->prepare($sqlQuery);
        $checkUsername->execute([
            'username' => $username,
        ]);
        return ($checkUsername->rowCount() > 0);
    }

    public static function emailExists(string $email): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'SELECT * FROM users WHERE email = :email';
        $checkEmail = $pdo->prepare($sqlQuery);
        $checkEmail->execute([
            'email' => $email,
        ]);
        return ($checkEmail->rowCount() > 0);
    }

    public static function createUser(string $username, string $email, string $password): string|false
    {
        $pdo = Database::getInstance()->getConnection();
        $hashedPasswd = password_hash($password, PASSWORD_DEFAULT);
        $confirmationToken = bin2hex(random_bytes(32));
        $sqlQuery = 'INSERT INTO users (username, email, password, confirmation_token)
                    VALUES (:username, :email, :hashedPasswd, :confirmationToken)';
        $registerUser = $pdo->prepare($sqlQuery);
        $success = $registerUser->execute([
            'username' => $username,
            'email' => $email,
            'hashedPasswd' => $hashedPasswd,
            'confirmationToken' => $confirmationToken
        ]);
        if ($success) {
            return $confirmationToken;
        }
        return false;
    }

    public static function confirmUser(string $token): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'UPDATE users SET is_confirmed = 1, confirmation_token = NULL WHERE confirmation_token = :token';
        $confirmUser = $pdo->prepare($sqlQuery);
        $confirmUser->execute(['token' => $token]);
        return ($confirmUser->rowCount() > 0);
    }

    public static function updateUser(int $id, string $username, string $email, ?string $password, int $notifications_enabled): bool
    {
        $pdo = Database::getInstance()->getConnection();
        if ($password === null) {
            $sqlQuery = 'UPDATE users SET username = :username, email = :email, notify_on_comment = :notify_on_comment WHERE id = :id';
            $updateUser = $pdo->prepare($sqlQuery);
            return $updateUser->execute([
                'username' => $username,
                'email' => $email,
                'notify_on_comment' => $notifications_enabled,
                'id' => $id
            ]);
        }
        $hashedPasswd = password_hash($password, PASSWORD_DEFAULT);
        $sqlQuery = 'UPDATE users SET username = :username, email = :email, password = :hashedPasswd, notify_on_comment = :notify_on_comment WHERE id = :id';
        $updateUser = $pdo->prepare($sqlQuery);
        return $updateUser->execute([
            'username' => $username,
            'email' => $email,
            'hashedPasswd' => $hashedPasswd,
            'notify_on_comment' => $notifications_enabled,
            'id' => $id
        ]);
    }

    public static function setPasswordResetToken(string $email, string $token, string $expiry): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'UPDATE users SET reset_token = :token, reset_token_expire_at = :expiry WHERE email = :email';
        $setToken = $pdo->prepare($sqlQuery);
        return $setToken->execute([
            'token' => $token,
            'expiry' => $expiry,
            'email' => $email
        ]);
    }

    public static function TokenValide(string $token): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'SELECT * FROM users WHERE reset_token = :token AND reset_token_expire_at > NOW()';
        $checkToken = $pdo->prepare($sqlQuery);
        $checkToken->execute(['token' => $token]);
        return ($checkToken->rowCount() > 0);
    }

    public static function resetPassword(string $token, string $newPassword): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $hashedPasswd = password_hash($newPassword, PASSWORD_DEFAULT);
        $sqlQuery = 'UPDATE users SET password = :hashedPasswd, reset_token = NULL, reset_token_expire_at = NULL WHERE reset_token = :token';
        $resetPassword = $pdo->prepare($sqlQuery);
        return $resetPassword->execute([
            'hashedPasswd' => $hashedPasswd,
            'token' => $token
        ]);
    }

    public static function getUserByEmail(string $email): ?array
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'SELECT * FROM users WHERE email = :email';
        $getUser = $pdo->prepare($sqlQuery);
        $getUser->execute(['email' => $email]);
        return $getUser->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function getUserByUsername(string $username): ?array
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'SELECT * FROM users WHERE username = :username';
        $getUser = $pdo->prepare($sqlQuery);
        $getUser->execute(['username' => $username]);
        return $getUser->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public static function getUserById(int $id): ?array
    {
        $pdo = Database::getInstance()->getConnection();
        $sqlQuery = 'SELECT * FROM users WHERE id = :id';
        $getUser = $pdo->prepare($sqlQuery);
        $getUser->execute(['id' => $id]);
        return $getUser->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}

?>