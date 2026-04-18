<?php

include "../includes/database.php";


session_start();
header('Content-Type: application/json');

$id = $_SESSION['id'];

$query = "SELECT `name`,`email`,`phone` FROM `users` WHERE `id` = '$id'"; 

$result = mysqli_query($con, $query);

$row = mysqli_fetch_assoc($result);
$name = $row['name'];
$email = $row['email'];
$phone = $row['phone'];

$data = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone
];

echo json_encode($data);