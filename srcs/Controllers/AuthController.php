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
            $token = User::createUser($username, $email, $password);
            if ($token !== false) {
                $subject = "Confirm your registration";
                $message = "Please click the following link to confirm your registration: ";
                $message .= "http://localhost:80/confirm?token=" . urlencode($token);
                $headers = "From: no-reply@camagru.local";
                mail($email, $subject, $message, $headers);
                require __DIR__ . '/../Views/auth/register_success.php';
            }
            else {
                $errors[] = "An error occurred while creating the user. Please try again.";
                require __DIR__ . '/../Views/auth/register.php';
                return;
            }
        } else {
            require __DIR__ . '/../Views/auth/register.php';
        }
    }

    public function confirmRegistration(): void
    {
        if (!isset($_GET['token'])) {
        require __DIR__ . '/../Views/auth/confirmation_failure.php';
        return;
        }

        $token = $_GET['token'];

        if (User::confirmUser($token)) {
            require __DIR__ . '/../Views/auth/confirmation_success.php';
        } else {
            require __DIR__ . '/../Views/auth/confirmation_failure.php';
        }
    }

    public function showLoginForm(): void
    {
        $errors = [];
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void
    {
        $errors = [];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = User::getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            if (!$user['is_confirmed']) {
                $errors[] = "Please confirm your email before logging in.";
                require __DIR__ . '/../Views/auth/login.php';
                return;
            }
            $_SESSION['user_id'] = $user['id'];
            header('Location: /');
            return;
        } else {
            $errors[] = "Invalid username or password.";
            require __DIR__ . '/../Views/auth/login.php';
        }
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /');
    }

    private function validationRegistration($username, $email, $password): array
    {
        $errors = [];

        if (strlen($username) === 0 || strlen($email) === 0 || strlen($password) === 0) {
            $errors[] = "All fields are required.";
        }

        elseif (strlen($username) < 5 || strlen($username) > 50){
            $errors[] = "Invalid Username. The username must contain between 5 and 50 characters";
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)){
            $errors[] = "Invalid Username. Allowed: Letters, numbers, - and _.";
        } elseif (User::usernameExists($username)){
                $errors[] = "Username already exists.";
        }

        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid Email, must be in format exemple@exemple.ex";
        } elseif (User::emailExists($email)){
            $errors[] = "An account already exists with this email, please sign Up.";
        }

        elseif (strlen($password) < 8 || strlen($password) > 255){
            $errors[] = "Invalid Password. The password must contain between 8 and 255 characters";
        }

        elseif (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must contain at least one numeric digit.";
        }
        elseif (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must contain at least one lowercase letter.";
        }
        elseif (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must contain at least one uppercase letter.";
        }
        elseif (!preg_match('/[`~!@#$%^&*()\-_=+{}[\]:;"\'<>,.?\/|\\\\]/', $password)) {
            $errors[] = "Password must contain at least one special character.";
        }

        return $errors;
    }
}
?>