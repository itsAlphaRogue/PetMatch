<?php

include "../includes/database.php";

session_start();

if($_SERVER['REQUEST_METHOD']=='POST')
{
    // From user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $id = $_SESSION['id'];

    $selectquery = "SELECT * FROM users WHERE id = '$id'";
    $dbuser = mysqli_fetch_assoc(mysqli_query($con, $selectquery));

    // From database
    $dbname = $dbuser['name'];
    $dbemail = $dbuser['email'];
    $dbphone = $dbuser['phone'];

    if($name != $dbname)
    {
         if (empty($name)) 
        {
            echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Name cannot be empty</p>
                END;
        } 
        elseif (strlen($name) < 3) 
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Name must be 3 or more characters</p>
            END;
        } 
        elseif (!ctype_alnum($name)) 
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Name must not contain special characters</p>
            END;
        }
        else
        {
            $query = "UPDATE `users` SET `name`='$name' WHERE `id` = '$id'";

            $result = mysqli_query($con, $query);
            if($result)
            {
                echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Name updated</p>
                END;
            }
            else
            {
                echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Something went wrong. Please try again</p>
                END;
            }
        }
    }

    if($dbemail != $email)
    {
        if (empty($email)) 
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Email can't be empty</p>
            END;
        } 
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Please enter a valid email</p>
            END;
        }
        elseif(mysqli_num_rows(mysqli_query($con,"SELECT id FROM users WHERE email='$email' LIMIT 1"))>0)
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Email already exists. Please use another</p>
            END;
        }
        else
        {
            $query = "UPDATE `users` SET `email`='$email' WHERE `id` = '$id'";

            $result = mysqli_query($con, $query);
            if($result)
            {
                echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Email updated</p>
                END;
            }
            else
            {
                echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Something went wrong. Please try again</p>
                END;
            }
        }
    }

    if($dbphone != $phone)
    {
        if (empty($phone)) 
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Phone number can't be empty</p>
            END;
        } 
        elseif (!ctype_digit($phone) || strlen($phone) !== 10) 
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Phone number must be 10 digits</p>
            END;
        }
        elseif(mysqli_num_rows(mysqli_query($con,"SELECT id FROM users WHERE phone='$phone' LIMIT 1"))>0)
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Phone number already exists.Please use another one</p>
            END;
        }
        else
        {
            $query = "UPDATE `users` SET `phone`='$phone' WHERE `id` = '$id'";

            $result = mysqli_query($con, $query);
            if($result)
            {
                echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Phone no updated</p>
                END;
            }
            else
            {
                echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Something went wrong. Please try again</p>
                END;
            }
        }
    }


    if ($name == $dbname && $email == $dbemail && $phone == $dbphone) {
        echo <<<END
        <p class="fixed right-10 bottom-14 rounded-xl bg-yellow-300 px-8 py-4 text-center">No changes detected</p>
        END;
    }
}

