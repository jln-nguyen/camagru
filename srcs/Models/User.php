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

    public static function createUser(string $username, string $email, string $password): bool
    {
        $pdo = Database::getInstance()->getConnection();
        $hashedPasswd = password_hash($password, PASSWORD_DEFAULT);
        $confirmationToken = bin2hex(random_bytes(32));
        $sqlQuery = 'INSERT INTO users (username, email, password, confirmation_token)
                    VALUES (:username, :email, :hashedPasswd, :confirmationToken)';
        $registerUser = $pdo->prepare($sqlQuery);
        return $registerUser->execute([
            'username' => $username,
            'email' => $email,
            'hashedPasswd' => $hashedPasswd,
            'confirmationToken' => $confirmationToken
        ]);
    }
}

?>