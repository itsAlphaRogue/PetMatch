<?php

include '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $phone = $_POST['phone'];

    // Name validation
    if (empty($name)) 
    {
        echo "Name cannot be empty";
    } 
    elseif (strlen($name) < 3) 
    {
        echo "Name must be 3 or more characters";
    } 
    elseif (!ctype_alnum($name)) 
    {
        echo "Name must not contain special characters";
    }

    // Email validation
    elseif (empty($email)) 
    {
        echo "Email can't be empty";
    } 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        echo "Please enter a valid email";
    }
    elseif(mysqli_num_rows(mysqli_query($con,"SELECT id FROM users WHERE email='$email' LIMIT 1"))>0)
    {
        echo "Email already exists. Please use another";
    }

    // Password validation
    elseif (empty($password1) || empty($password2)) 
    {
        echo "Please fill both password fields";
    } 
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password1)) 
    {
        echo "Password must contain:
        <br>1 Uppercase letter
        <br>1 Lowercase letter
        <br>1 digit
        <br>1 special character
        <br>Minimum 8 characters";
    } 
    elseif ($password1 !== $password2) 
    {
        echo "Passwords don't match";
    }

    // Phone validation
    elseif (empty($phone)) 
    {
        echo "Phone number can't be empty";
    } 
    elseif (!ctype_digit($phone) || strlen($phone) !== 10) 
    {
        echo "Phone number must be 10 digits";
    }
    elseif(mysqli_num_rows(mysqli_query($con,"SELECT id FROM users WHERE phone='$phone' LIMIT 1"))>0)
    {
        echo "Phone number already exists.Please use another one";
    }



    else
    {
        $hashedpassword = password_hash($password1,PASSWORD_DEFAULT);
        mysqli_query($con,"INSERT INTO `users`(`name`, `email`, `password`, `phone`) VALUES ('$name','$email','$hashedpassword','$phone')");

        echo "success";
    }

}
?>
