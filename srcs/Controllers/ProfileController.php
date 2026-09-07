<?php

class ProfileController
{
    public function showProfile(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $user = User::getUserById($userId);
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $username = $user['username'];
        $email = $user['email'];
        $notifications_enabled = $user['notify_on_comment'];
        require __DIR__ . '/../Views/profile/profile.php';
    }

    public function showModifyProfileForm(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $errors = [];
        $userId = $_SESSION['user_id'];
        $user = User::getUserById($userId);
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $username = $user['username'];
        $email = $user['email'];
        $notifications_enabled = $user['notify_on_comment'];
        require __DIR__ . '/../Views/profile/modify_profile.php';
    }

    public function modifyProfile(): void
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        if ($_POST['change_password'] == '1'){
            if (empty($password)) {
                $errors[] = "Password is required when changing password.";
                require __DIR__ . '/../Views/profile/modify_profile.php';
                return;
            }
        } else {
            $password = null;
        }

        if (isset($_POST['notify_on_comment'])) {
            $notifications_enabled = 1;
        } else {
            $notifications_enabled = 0;
        }

        $errors = $this->validationModifyProfile($username, $email, $password);

        if (empty($errors)) {
            if (!User::updateUser($_SESSION['user_id'], $username, $email, $password, $notifications_enabled)) {
                $errors[] = "An error occurred while updating the user. Please try again.";
            } else {
                header('Location: /profile');
                return;
            }
        }
        require __DIR__ . '/../Views/profile/modify_profile.php';
    }

    public function showForgotPasswordForm(): void
    {
        require __DIR__ . '/../Views/auth/forgot_password.php';
    }

    public function forgotPassword(): void
    {
        $email = $_POST['email'];
        $user = User::getUserByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(32));
            User::setPasswordResetToken($user['email'], $token, date('Y-m-d H:i:s', strtotime('+15 minutes')));
            $subject = "Reset your password";
            $message = "Please click the following link to reset your password: ";
            $message .= "http://localhost:80/reset-password?token=" . urlencode($token);
            $message .= "\nThis link will expire in 15 minutes.";
            $headers = "From: no-reply@camagru.local";
            mail($email, $subject, $message, $headers);
        }
        require __DIR__ . '/../Views/auth/forgot_password_success.php';
    }

    public function showResetPasswordForm(): void
    {
        if (!isset($_GET['token'])) {
        require __DIR__ . '/../Views/auth/token_expired_error.php';
        return;
        }

        $token = $_GET['token'];

        if (User::tokenValide($token)) {
            require __DIR__ . '/../Views/auth/reset_password.php';
        } else {
            require __DIR__ . '/../Views/auth/token_expired_error.php';
        }
    }

    public function resetPassword(): void
    {
        if (!isset($_POST['token'])) {
            require __DIR__ . '/../Views/auth/token_expired_error.php';
            return;
        }

        $token = $_POST['token'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
            require __DIR__ . '/../Views/auth/reset_password.php';
            return;
        }

        if ($newPassword !== null) {
            if (strlen($newPassword) < 8 || strlen($newPassword) > 255){
                $errors[] = "Invalid Password. The password must contain between 8 and 255 characters";
            }
            elseif (!preg_match('/[0-9]/', $newPassword)) {
                $errors[] = "Password must contain at least one numeric digit.";
            }
            elseif (!preg_match('/[a-z]/', $newPassword)) {
                $errors[] = "Password must contain at least one lowercase letter.";
            }
            elseif (!preg_match('/[A-Z]/', $newPassword)) {
                $errors[] = "Password must contain at least one uppercase letter.";
            }
            elseif (!preg_match('/[`~!@#$%^&*()\-_=+{}[\]:;"\'<>,.?\/|\\\\]/', $newPassword)) {
                $errors[] = "Password must contain at least one special character.";
            }
        }
        if (!empty($errors)) {
            require __DIR__ . '/../Views/auth/reset_password.php';
            return;
        }
        User::resetPassword($token, $newPassword);
        require __DIR__ . '/../Views/auth/reset_password_success.php';
    }

    private function validationModifyProfile($username, $email, $password): array
    {
        $errors = [];
        $user = User::getUserById($_SESSION['user_id']);
        if (!$user) {
            $errors[] = "An error occurred while fetching the user. Please try again.";
            return $errors;
        }
        
        if (strlen($username) === 0 || strlen($email) === 0 || ($password !== null && strlen($password) === 0)) {
            $errors[] = "All fields are required.";
        }

        elseif (strlen($username) < 5 || strlen($username) > 50){
            $errors[] = "Invalid Username. The username must contain between 5 and 50 characters";
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)){
            $errors[] = "Invalid Username. Allowed: Letters, numbers, - and _.";
        } elseif (User::usernameExists($username) && $username !== $user['username']){
                $errors[] = "Username already exists.";
        }

        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid Email, must be in format exemple@exemple.ex";
        } elseif (User::emailExists($email) && $email !== $user['email']){
            $errors[] = "An account already exists with this email, please sign Up.";
        }

        if ($password !== null) {
            if (strlen($password) < 8 || strlen($password) > 255){
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
        }

        return $errors;
    }

}