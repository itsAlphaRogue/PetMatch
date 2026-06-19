<?php

use PetMatch\Service\PetService;
use PetMatch\Repository\BreedRepository;

include '../includes/database.php';

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $data = json_decode(file_get_contents("php://input"), true);
    $petid = isset($data['id']) ? (int)$data['id'] : 0;

    $petService = new PetService();
    $pet = $petService->getPetById($petid);

    if(!$pet)
    {
        echo "Pet not found";
        exit;
    }

    $image = $pet->getImage();
    $name = $pet->getName();
    $breedid = $pet->getBreedId();
    $age = $pet->getAge();
    $gender = $pet->getGender();
    $description = $pet->getDescription();

    // FOR BREEDS
    $breedRepo = new BreedRepository();
    $breeds = $breedRepo->getAll();
    $breed_options = '';

    foreach ($breeds as $breed_row) {
        $selected = ($breed_row['id'] == $breedid) ? 'selected' : '';
        $breed_options .= "<option value='{$breed_row['id']}' {$selected}>{$breed_row['name']}</option>";
    }

    // For AGE
    $puppy = ($age === 'Puppy') ? 'selected' : '';
    $young = ($age === 'Young') ? 'selected' : '';
    $adult = ($age === 'Adult') ? 'selected' : '';

    // For GENDER
    $male = ($gender === 'Male') ? 'selected' : '';
    $female = ($gender === 'Female') ? 'selected' : '';

    echo <<<END
            <div class="gap-4 border border-black px-4 py-4">
                <form id="createpet" onsubmit="updatePet(event)" enctype="multipart/form-data">
                    <div class="flex flex-col gap-4 lg:flex-row">
                        <!-- div for image -->
                        <div class="flex justify-center">
                            <div id="preview-box" onclick="triggerFileInput()" class="flex size-60 cursor-pointer flex-col items-center justify-center gap-4 rounded-md border border-black/10 bg-neutral-200 bg-cover bg-center bg-no-repeat transition-colors hover:bg-neutral-300 active:bg-neutral-400">
                                <svg class="h-8 fill-black/60" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M480-480ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h320v80H200v560h560v-280h80v280q0 33-23.5 56.5T760-120H200Zm40-160h480L570-480 450-320l-90-120-120 160Zm480-280v-167l-64 63-56-56 160-160 160 160-56 56-64-63v167h-80Z" /></svg>
                                <p class="text-black/60">Click to upload image</p>
                            </div>
                            <input type="file" id="image-input" onchange="previewImage(event)" class="hidden" accept="image/*" data-filename="assets/images/pets/$image" value="assets/images/pets/$image" name="pet_image"  />
                        </div>
                        <!-- div for input tags of pet upload -->
                        <div class="w-full">
                            <div class="mb-4 grid grid-cols-2 gap-4">
                                <!-- Group 1: Name -->
                                <div class="flex flex-col">
                                    <label for="name" class="mb-1">Name</label>
                                    <input name="name" type="text" class="border border-black p-2 outline-none" value="$name" required />
                                </div>
                                <!-- Group 2: Breed -->
                                <div class="flex flex-col">
                                    <label for="breed" class="mb-1">Breed</label>
                                    <select name="breed" class=" border border-black p-2 outline-none" value="$breedid" required>
                                        <option value="" class="hidden"></option>
                                        $breed_options
                                    </select>
                                </div>
                                <!-- Group 3: Age -->
                                <div class="flex flex-col">
                                    <label for="age" class="mb-1">Age</label>
                                    <select name="age" class="border border-black p-2 outline-none" value="$age" required>
                                        <option value="" class="hidden"></option>
                                        <option value="Puppy" $puppy>Puppy</option>
                                        <option value="Young" $young>Young</option>
                                        <option value="Adult" $adult>Adult</option>
                                    </select>
                                </div>
                                <!-- Group 4: gender -->
                                <div class="flex flex-col">
                                    <label for="gender" class="mb-1">Gender</label>
                                    <select name="gender" class="border border-black p-2 outline-none" default="$gender" required>
                                        <option value="" class="hidden"></option>
                                        <option value="Male" $male>Male</option>
                                        <option value="Female" $female>Female</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <textarea name="description" class="h-[70px] w-full resize-none border border-black p-2 outline-none" placeholder="A short description about this pet"  required>$description</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-center gap-4 lg:justify-start">
                        <input class="h-12 w-40 cursor-pointer rounded-sm bg-blue-500 px-8 py-3 transition-colors hover:bg-blue-300 active:bg-blue-600" type="submit" value="Update" />
                    </div>
                </form>
            </div>

            <!-- div to display message after a pet is created -->
            <div id="messagebox" class="transition-all"></div>

            <script defer>
            function previewPetImage() {
                const existingFileName = document.getElementById('image-input').dataset.filename;
                const previewbox = document.getElementById("preview-box");
                previewbox.innerHTML = '';
                previewbox.style.backgroundImage = "url("+existingFileName+")";
            }previewPetImage();
            </script>
    END;
}
else
{
    echo "admindashboard";
}