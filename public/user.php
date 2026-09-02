<?php

require './Class/autoloader.php'; 
Autoloader::register(); 


$username = $email = $password = "";
$username = "tests";
try
{
    $pdo = Database::getInstance()->getConnection();
    if (strlen($username) > 50 or strlen($username) < 5)
    {
        echo "Username must contain between 5 and 50 characters.";
    }
    $sqlQuery = 'SELECT * FROM users WHERE username = :username';
    $checkUsername = $pdo->prepare($sqlQuery);
    $checkUsername->execute([
        'username' => $username,
    ]);
    if ($checkUsername->rowCount() == 0)
    {
        echo "Username available.";
    }
    else
    {
        echo "Username already taken, please choose another username.";  
    }
    $sqlQuery = 'SELECT * FROM users WHERE email = :email';
    $checkUsername = $pdo->prepare($sqlQuery);
    $checkUsername->execute([
        'email' => $email,
    ]);
    if ($checkUsername->rowCount() == 0)
    {
        echo "email available.";
    }
    else
    {
        echo "An account is already associated to this email, please signin.";
    }

}
catch (Exception $e)
{
    die("Error : " . $e->getMessage());
} 

?>