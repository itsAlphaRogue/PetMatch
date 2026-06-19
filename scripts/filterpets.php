<?php

use PetMatch\Service\PetService;

include '../includes/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $inputage = $_POST['age'] ?? null;
    $inputgender = $_POST['gender'] ?? null;
    $inputbreed = !empty($_POST['breed']) ? (int)$_POST['breed'] : null;

    $petService = new PetService();
    $pets = $petService->filterPets($inputage, $inputgender, $inputbreed);

    if(empty($pets))
    {
        echo <<<END
        <p class="flex w-full items-center justify-center py-32 text-3xl">
            No pets found. Try different keywords.
        </p>
        END;
    }
    else
    {
        foreach ($pets as $pet)
        {
            $id = $pet->getId();
            $name = $pet->getName();
            $image = $pet->getImage();
            $breed = $pet->getBreedName();
            $age = $pet->getAge();
            $gender = $pet->getGender();

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
    }
}