<?php

include "../includes/database.php";

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $breedid = $_POST['id'];

    $query = "DELETE FROM `breeds` WHERE `id` = $breedid";

    $result = mysqli_query($con, "SELECT `id` FROM `pets` WHERE `breed_id`='$breedid'");
    if(mysqli_num_rows($result)>0) 
    {
        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Can't delete breed. Pet with this breed exists.</p>
        END;
    }
    else
    {
        if(mysqli_query($con,$query))
        {
            echo <<<END
            
            <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Breed deleted</p>
            END;
        }
        else
        {
            echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Something went wrong</p>
            END;
        }
    }
}