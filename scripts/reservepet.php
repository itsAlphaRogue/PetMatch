<?php

use PetMatch\Service\ReservationService;

include "../includes/database.php";

session_start();

$data = json_decode(file_get_contents("php://input"), true);
$petid = isset($data['id']) ? (int)$data['id'] : 0;

if(!isset($_SESSION['user']))
{
    $_SESSION['redirect_petid'] = $petid;
    echo 'redirect';
    exit;
}

$userid = $_SESSION['id'];

$reservationService = new ReservationService();
$reservationService->createReservation($petid, $userid);