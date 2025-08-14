<?php
session_start();

include 'includes/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['submit']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];


    $result = mysqli_query($con, "SELECT `id`,`password` FROM `admins` WHERE `username` = '$username'");


    if(mysqli_num_rows($result)==0) 
    {
        echo <<<END
                <p class="w-fit rounded-lg bg-red-400 px-4 py-2 text-center">Incorrect Username or Password</p>
        END;
    }
    else
    {
        $row = mysqli_fetch_assoc($result);

        $hashedpassword = $row['password'];
        if(password_verify($password,$hashedpassword))
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