<?php

include "../includes/database.php";

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $rawdata = file_get_contents("php://input");
    $breeddata = json_decode($rawdata,true);

    $id = $breeddata['id'];
    $name = $breeddata['breed'];

    $query = "UPDATE `breeds` SET `name`='$name' WHERE `id` = '$id'";
    
    if(mysqli_query($con, $query))
    {
        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Breed Updated</p>
        END;
    }
    else
    {
        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Something went wrong</p>
        END;
    }
}