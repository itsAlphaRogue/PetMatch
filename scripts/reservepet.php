<?php

include "../includes/database.php";

session_start();

$data = json_decode(file_get_contents("php://input"), true);

// setting pet's id to later redirect using js
if(!isset($_SESSION['user']))
{
    $_SESSION['redirect_petid'] = $data['id'];
    echo 'redirect';
    exit;
}

// To create adoption request
$petid = $data['id'];
$userid = $_SESSION['id'];
$adoptionquery = "INSERT INTO `adoption_requests`(`pet_id`,`user_id`) VALUES($petid,$userid)"; 


// After creating adopion request also change pet status to reserved
$petquery = "UPDATE `pets` SET `status` = 'Reserved' WHERE `id`=$petid";

mysqli_query($con, $adoptionquery);
mysqli_query($con, $petquery);