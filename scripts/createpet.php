<?php

use PetMatch\Service\PetService;

include '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $breed = !empty($_POST['breed']) ? (int)$_POST['breed'] : null;
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $description = $_POST['description'] ?? '';

    if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] == 0) {
        $image = $_FILES['pet_image'];
        $imageName = $image['name'];
        $imageTmp = $image['tmp_name'];

        $ext = pathinfo($imageName, PATHINFO_EXTENSION);
        $uniqueName = uniqid('pet_', true) . "." . $ext;
        $destination = "../assets/images/pets/" . $uniqueName;

        if (move_uploaded_file($imageTmp, $destination)) {
            $petService = new PetService();
            $petService->createPet($name, $breed, $age, $gender, $description, $uniqueName);
            echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Pet created successfully</p>
            END;
        } else {
            echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Upload failed</p>
            END;
        }
    } else {
        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">No image present or upload failed</p>
        END;
    }
}
