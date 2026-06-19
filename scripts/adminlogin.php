<?php
session_start();

use PetMatch\Repository\UserRepository;

include 'includes/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['submit']))
{
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $userRepo = new UserRepository();
    $admin = $userRepo->findAdminByUsername($username);

    if(!$admin) 
    {
        echo <<<END
                <p class="w-fit rounded-lg bg-red-400 px-4 py-2 text-center">Incorrect Username or Password</p>
        END;
    }
    else
    {
        $hashedpassword = $admin->getPassword();
        if(password_verify($password, $hashedpassword))
        {
            unset($_SESSION['user']);
            $_SESSION['admin'] = $username;
            header('Location: admindashboard');
            exit(); 
        }
        else
        {
            echo <<<END
                <p class="w-fit rounded-lg bg-red-400 px-4 py-2 text-center">Incorrect Username or Password</p>
            END;
        }
    }
}