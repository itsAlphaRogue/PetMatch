<?php

use PetMatch\Repository\UserRepository;

include "../includes/database.php";

session_start();
header('Content-Type: application/json');

$id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

$userRepo = new UserRepository();
$user = $userRepo->findById($id);

$data = [];
if ($user) {
    $data = [
        'name' => $user->getName(),
        'email' => $user->getEmail(),
        'phone' => $user->getPhone()
    ];
}

echo json_encode($data);