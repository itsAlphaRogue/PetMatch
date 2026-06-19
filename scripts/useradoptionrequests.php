<?php

use PetMatch\Service\ReservationService;

include "../includes/database.php";

if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}

$userid = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

$reservationService = new ReservationService();
$reservations = $reservationService->getReservationsByUserId($userid);

if(empty($reservations))
{
    echo <<<END
    <p class="flex w-full items-center justify-center py-32 text-3xl">
        No adoptions request made.
    </p>
    END;
}
else
{
    foreach ($reservations as $reservation)
    {
        $pet = $reservation->getPet();
        if (!$pet) {
            continue;
        }

        $image = $pet->getImage();
        $petname = $pet->getName();
        $age = $pet->getAge();
        $gender = $pet->getGender();
        $breed_name = $pet->getBreedName();
        $status = $reservation->getStatus();
        $color = $reservation->getStatusColorClass();
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
                        <p class="w-28">Status</p>
                        <p>: &nbsp</p>
                        <p class="$color">$status</p>
                    </span>

                    <span class="flex">
                        <p class="w-28">Requested at</p>
                        <p>: $requested_at</p>
                    </span>
                </div>
            </div>
        END;
    }
}