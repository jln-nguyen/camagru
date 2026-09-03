<?php

class AuthController
{
    public function showRegisterForm(): void
    {
        $errors = [];
        require __DIR__ . '/../Views/auth/register.php';
    }

    public function register(): void
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = $this->validationRegistration($username, $email, $password);

        if (empty($errors)) {
            User::createUser($username, $email, $password);
            echo "User has been created successfully.";
        } else {
            require __DIR__ . '/../Views/auth/register.php';
        }
    }

    private function validationRegistration($username, $email, $password): array
    {
        $errors = [];

        if (strlen($username) < 5 || strlen($username) > 50){
            $errors[] = "Invalid Username. The username must contain between 5 and 50 characters";
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)){
            $errors[] = "Invalid Username. Allowed: Letters, numbers, - and _.";
        } elseif (User::usernameExists($username)){
                $errors[] = "Username already exists.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid Email, must be in format exemple@exemple.ex";
        } elseif (User::emailExists($email)){
            $errors[] = "An account already exists with this email, please sign Up.";
        }

        if (strlen($password) < 8 || strlen($password) > 255){
            $errors[] = "Invalid Password. The password must contain between 8 and 255 characters";
        }

        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one numeric digit.";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        if (!preg_match('/[`~!@#$%^&*()\-_=+{}[\]:;"\'<>,.?\/|\\\\]/', $password)) {
            $errors[] = "Password must contain at least one special character.";
        }

        return $errors;
    }
}
?>