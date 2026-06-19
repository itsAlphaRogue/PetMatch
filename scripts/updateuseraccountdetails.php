<?php

use PetMatch\Repository\UserRepository;

include "../includes/database.php";

session_start();

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

    $userRepo = new UserRepository();
    $dbuser = $userRepo->findById($id);

    if (!$dbuser) {
        echo <<<END
        <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">User not found</p>
        END;
        exit;
    }

    $dbname = $dbuser->getName();
    $dbemail = $dbuser->getEmail();
    $dbphone = $dbuser->getPhone();

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
            if($userRepo->updateDetails($id, $name, $dbemail, $dbphone))
            {
                $dbname = $name;
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
        elseif($userRepo->findByEmail($email) !== null)
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Email already exists. Please use another</p>
            END;
        }
        else
        {
            if($userRepo->updateDetails($id, $dbname, $email, $dbphone))
            {
                $dbemail = $email;
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
        elseif($userRepo->findByPhone($phone) !== null)
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Phone number already exists.Please use another one</p>
            END;
        }
        else
        {
            if($userRepo->updateDetails($id, $dbname, $dbemail, $phone))
            {
                $dbphone = $phone;
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

    if ($name == $dbuser->getName() && $email == $dbuser->getEmail() && $phone == $dbuser->getPhone()) {
        echo <<<END
        <p class="fixed right-10 bottom-14 rounded-xl bg-yellow-300 px-8 py-4 text-center">No changes detected</p>
        END;
    }
}
