<?php

include '../includes/database.php';
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = mysqli_query($con, "SELECT `id`, `name`, `password` FROM users WHERE email = '$email'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) == 0) 
    {
        $response['status'] = 'error';
        $response['message'] = 'Incorrect email or password';
    } else 
    {
        $hashedpassword = $row['password'];
        if (password_verify($password, $hashedpassword)) 
        {
            session_start();
            unset($_SESSION['admin']);
            $_SESSION['user'] = $row['name'];
            $_SESSION['id'] = $row['id'];

            if (isset($_SESSION['redirect_petid'])) 
            {
                $response['status'] = 'redirect';
                $response['petid'] = $_SESSION['redirect_petid'];
                unset($_SESSION['redirect_petid']); // Optional: clean up
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
