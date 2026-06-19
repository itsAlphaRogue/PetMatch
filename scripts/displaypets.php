<?php

use PetMatch\Service\PetService;

include "../includes/database.php";

$petService = new PetService();

$data = json_decode(file_get_contents("php://input"), true);
$limit = !empty($data['limit']) ? (int)$data['limit'] : 0;

if ($limit > 0) {
    $pets = $petService->getAvailablePets($limit);
} else {
    $pets = $petService->getAllPets();
}

foreach ($pets as $pet) {
    $id = $pet->getId();
    $image = $pet->getImage();
    $name = $pet->getName();
    $age = $pet->getAge();
    $gender = $pet->getGender();
    $breed = $pet->getBreedName();

    echo <<<END
    <a href="pet?id=$id">
        <div class="card-shadow max-w-xs overflow-hidden rounded-3xl">
            <div class="h-64 overflow-hidden">
                <img class="w-full" src="assets/images/pets/$image" alt="" />
            </div>
            <div class="mx-4 my-4 flex flex-col gap-2">
                <p class="text-2xl font-bold">$name</p>
                <p class="text-neutral-600">$breed</p>
                <p class="text-neutral-600">$age ● $gender</p>
            </div>
        </div>
    </a>
    END;
}