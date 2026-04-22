<?php

// Silently logs a pet page view into pet_views.
// Called by pet.js as fire-and-forget — returns no output.
// Ignores invalid or missing pet_id gracefully.

include "../includes/database.php";

$data   = json_decode(file_get_contents("php://input"), true);
$pet_id = isset($data['id']) ? (int)$data['id'] : 0;

if ($pet_id <= 0) exit;

// Verify the pet actually exists before inserting
$check = mysqli_query($con, "SELECT id FROM `pets` WHERE `id` = $pet_id LIMIT 1");
if (mysqli_num_rows($check) === 0) exit;

mysqli_query($con, "INSERT INTO `pet_views` (`pet_id`) VALUES ($pet_id)");