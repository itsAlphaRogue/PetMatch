<?php

use PetMatch\Service\PetService;

include "../includes/database.php";
$data = json_decode(file_get_contents("php://input"), true);

$petid = isset($data['id']) ? (int)$data['id'] : 0;
$petService = new PetService();
$pet = $petService->getPetById($petid);

if(!$pet)
{
    echo <<<END
        <div class="w-full flex justify-center items-center">
            <p class="text-2xl">Pet you searched was not found</p>
        </div> 
    END;
    exit;
}

$image = $pet->getImage();
$breed = $pet->getBreedName();
$name = $pet->getName();
$age = $pet->getAge();
$gender = $pet->getGender();
$description = $pet->getDescription();
$status = $pet->getDisplayStatus();
$colors = $pet->getStatusColors();
$reserve = $pet->getReserveButtonClass();

echo <<<END
    <div class="m-4 overflow-hidden rounded-lg lg:relative lg:m-0 lg:flex-[1] lg:rounded-none">
        <img class="aspect-square rounded-lg object-cover lg:absolute lg:h-full lg:rounded-none" src="assets/images/pets/$image" alt="" />
    </div>

    <div class="m-4 flex flex-col justify-center gap-8 lg:my-0 lg:flex-[2]">
        <div class="flex flex-col gap-4">
            <p class="font-poppins text-3xl font-bold">$name</p>
            <p class="text-neutral-600 capitalize text-xl">$breed</p>
            <p class="text-neutral-600 text-xl">$age ● $gender</p>
            <p class="text-justify">$description</p>
        </div>

        <div class="flex w-fit items-center justify-center rounded-3xl $colors px-3 py-1">
            <p class="mr-1 text-2xl">●</p>
            <p class="font-semibold">$status</p>
        </div>

        <div class="flex items-center justify-between gap-8">
            <div onclick="reservePet($petid)" class="h-fit rounded-md $reserve px-6 py-4">
                <p class="font-semibold">Reserve</p>
            </div>
            <div class="h-fit rounded-md bg-blue-600/30">
                <p class="m-2 font-semibold text-blue-500/95 italic">Please note: If you do not claim your reserved pet within three days of reservation, we may make them available for adoption again.</p>
            </div>
        </div>
    </div>
END;