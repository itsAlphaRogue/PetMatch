<?php

use PetMatch\Service\PetService;

include "../includes/database.php";

$id = (int)$_POST['id'];
$name = $_POST['name'] ?? '';
$breed_id = !empty($_POST['breed']) ? (int)$_POST['breed'] : null;
$age = $_POST['age'] ?? '';
$gender = $_POST['gender'] ?? '';
$description = $_POST['description'] ?? '';

$petService = new PetService();
$pet = $petService->getPetById($id);

if (!$pet) {
    echo <<<END
        <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">
            Pet not found
        </p>
    END;
    exit;
}

$currentImage = $pet->getImage();
$status = $pet->getStatus();
$newFileName = $currentImage;
$imageUpdated = false;

// Check if a new file was uploaded
if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['pet_image']['tmp_name'];
    $fileName = $_FILES['pet_image']['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    $newFileName = uniqid("pet_", true) . "." . strtolower($fileExtension);
    $uploadFolder = "../assets/images/pets/";
    $destPath = $uploadFolder . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $imageUpdated = true;

        $oldImagePath = $uploadFolder . $currentImage;
        if ($currentImage && file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }
}

$petService->updatePet($id, $name, $breed_id, $age, $gender, $status, $description, $newFileName);

echo <<<END
    <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">
        Pet updated successfully
    </p>
END;
