<?php

// Silently logs a pet page view into pet_views.
// Called by pet.js as fire-and-forget — returns no output.
// Ignores invalid or missing pet_id gracefully.

use PetMatch\Repository\PetRepository;
use PetMatch\Database;

include "../includes/database.php";

$data   = json_decode(file_get_contents("php://input"), true);
$pet_id = isset($data['id']) ? (int)$data['id'] : 0;

if ($pet_id <= 0) exit;

$petRepo = new PetRepository();
$pet = $petRepo->findById($pet_id);
if (!$pet) exit;

$db = Database::getConnection();
$stmt = $db->prepare("INSERT INTO `pet_views` (`pet_id`) VALUES (?)");
$stmt->bind_param("i", $pet_id);
$stmt->execute();