<?php

include "../includes/database.php";

if($_SERVER["REQUEST_METHOD"] == 'POST')
{
    $breedname = strtolower($_POST['addbreed']);

    $result = mysqli_query($con, "SELECT `name` FROM `breeds` WHERE `name`='$breedname'");
    if(mysqli_num_rows($result)>0)
    {
        echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">This breed is already created</p>
        END;
    }
    else
    {
        $query = "INSERT INTO `breeds`(`name`) VALUES('$breedname')";
    
        if(mysqli_query($con, $query))
        {
            echo <<<END
                    <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">Breed added successfully</p>
            END;
        }
        else
        {
            echo <<<END
                    <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Some error occured</p>
            END;
        }
    }
}