<?php

use PetMatch\Service\ReservationService;

include "../includes/database.php";

$reservationService = new ReservationService();
$reservations = $reservationService->getPendingReservations();

if(empty($reservations))
{
    echo <<<END
    <p class="flex w-full items-center justify-center py-32 text-3xl">
        No adoptions request.
    </p>
    END;
}
else
{
    foreach ($reservations as $reservation)
    {
        $pet = $reservation->getPet();
        $user = $reservation->getUser();
        
        if (!$pet || !$user) {
            continue;
        }

        $petid = $pet->getId();
        $image = $pet->getImage();
        $petname = $pet->getName();
        $age = $pet->getAge();
        $gender = $pet->getGender();
        $breed_name = $pet->getBreedName();

        $username = $user->getName();
        $phone = $user->getPhone();
        $requested_at = $reservation->getRequestedAt();

        echo <<<END
            <div class="m-8 w-fit">
                <!-- info about pet -->
                <div class="flex">
                    <img class="h-28" src="assets/images/pets/$image" alt="" />
                    <div class="mx-4 flex flex-col justify-around gap-2">
                        <p class="text-2xl font-bold">$petname</p>
                        <p class="text-neutral-600">$breed_name</p>
                        <p class="text-neutral-600">$age ● $gender</p>
                    </div>
                </div>

                <!-- info about requester -->
                <div class="mt-4 font-semibold">
                    <span class="flex">
                        <p class="w-28">Requested by</p>
                        <p>: $username</p>
                    </span>

                    <span class="flex">
                        <p class="w-28">Contact no</p>
                        <p>: $phone</p>
                    </span>

                    <span class="flex">
                        <p class="w-28">Requested at</p>
                        <p>: $requested_at</p>
                    </span>
                </div>

                <!-- Options (accept / reject) -->
                <div class="mt-4 flex justify-center gap-2">
                    <button onclick="setAdoptionRequestStatus('Approved',$petid)" class="h-9 w-full cursor-pointer rounded-sm bg-green-500 text-center font-semibold transition-colors hover:bg-green-300 active:bg-green-600">
                        <p>Accept</p>
                    </button>

                    <button onclick="setAdoptionRequestStatus('Rejected',$petid)" class="h-9 w-full cursor-pointer rounded-sm bg-red-500 text-center font-semibold transition-colors hover:bg-red-300 active:bg-red-600">
                        <p>Reject</p>
                    </button>
                </div>
            </div>
        END;
    }
}