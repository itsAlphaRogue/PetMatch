<?php

use PetMatch\Repository\UserRepository;

include '../includes/database.php';
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $userRepo = new UserRepository();
    $user = $userRepo->findByEmail($email);

    if (!$user) 
    {
        $response['status'] = 'error';
        $response['message'] = 'Incorrect email or password';
    } else 
    {
        if (password_verify($password, $user->getPassword())) 
        {
            session_start();
            unset($_SESSION['admin']);
            $_SESSION['user'] = $user->getName();
            $_SESSION['id'] = $user->getId();

            if (isset($_SESSION['redirect_petid'])) 
            {
                $response['status'] = 'redirect';
                $response['petid'] = $_SESSION['redirect_petid'];
                unset($_SESSION['redirect_petid']);
            } 
            else 
            {
                $response['status'] = 'redirect';
                $response['location'] = 'home';
            }
        } 
        else 
        {
            $response['status'] = 'error';
            $response['message'] = 'Incorrect email or password.';
        }
    }
}

echo json_encode($response);
exit;
