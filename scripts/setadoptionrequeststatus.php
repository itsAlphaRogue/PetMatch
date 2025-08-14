<?php
include "../includes/database.php";

$rawdata = file_get_contents("php://input");

$petdata = json_decode($rawdata, true);

$option = $petdata['option'];
$petid = $petdata['id'];

$adoptionrequestquery = "UPDATE `adoption_requests` SET `status` = '$option' WHERE `pet_id`=$petid";


switch($option)
{
    // When approved change status in adoption request as Approved and change pet status as adopted
    case "Approved":
        mysqli_query($con, $adoptionrequestquery);
        mysqli_query($con,"UPDATE `pets` SET `status`='Adopted' WHERE id=$petid");

        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Adoption request approved</p>
        END;
        break;

    // When rejected change status in adoption request as Rejected and change pet status as Available for adoption
    case "Rejected":
        mysqli_query($con,$adoptionrequestquery);
        mysqli_query($con,"UPDATE `pets` SET `status`='Available' WHERE id=$petid");

        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Adoption request rejected</p>
        END;
        break;

    default:
        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Some error occured. Refresh the page and try again</p>
        END;
}