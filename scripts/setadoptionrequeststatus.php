<?php

use PetMatch\Service\ReservationService;

include "../includes/database.php";

$rawdata = file_get_contents("php://input");
$petdata = json_decode($rawdata, true);

$option = $petdata['option'] ?? '';
$petid = isset($petdata['id']) ? (int)$petdata['id'] : 0;

$reservationService = new ReservationService();

switch($option)
{
    case "Approved":
        $reservationService->approveReservation($petid);
        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Adoption request approved</p>
        END;
        break;

    case "Rejected":
        $reservationService->rejectReservation($petid);
        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Adoption request rejected</p>
        END;
        break;

    default:
        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Some error occured. Refresh the page and try again</p>
        END;
}