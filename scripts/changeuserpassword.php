<?php

include "../includes/database.php";

session_start();

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $currentpassword = $_POST['currentpassword'];
    $newpassword = $_POST['newpassword'];
    $retypepassword = $_POST['retypepassword'];

    $id = $_SESSION['id'];
    $query = "SELECT `password` FROM `users` WHERE `id`='$id'";
    $row = mysqli_fetch_assoc(mysqli_query($con, $query));
    $dbpassword = $row['password'];

    // password validation
    if (empty($newpassword) || empty($retypepassword) || empty($currentpassword)) 
    {
        echo <<<END
        <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Please fill all password fields</p>
        END;
    } 
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $newpassword)) 
    {
        echo <<<END
        <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Password must contain:
        <br>1 Uppercase letter
        <br>1 Lowercase letter
        <br>1 digit
        <br>1 special character
        <br>Minimum 8 characters</p>
        END;
    } 
    elseif ($newpassword !== $retypepassword) 
    {
        echo <<<END
        <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Passwords don't match</p>
        END;
    }

    if(password_verify($currentpassword,$dbpassword))
    {
        $newhashedpassword = password_hash($newpassword,PASSWORD_DEFAULT);

        $query = "UPDATE `users` SET `password`='$newhashedpassword' WHERE `id`='$id'";
        if(mysqli_query($con, $query))
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Password Updated</p>
            END;
        }
        else
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Something went wrong</p>
            END;
        } 
    }
    else {
        echo <<<END
        <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Incorrect Password</p>
        END;
    }
}