<?php

use PetMatch\Repository\BreedRepository;

include "../includes/database.php";

if($_SERVER["REQUEST_METHOD"] == 'POST')
{
    $breedname = strtolower($_POST['addbreed']);

    $breedRepo = new BreedRepository();
    if($breedRepo->findByName($breedname) !== null)
    {
        echo <<<END
                <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">This breed is already created</p>
        END;
    }
    else
    {
        if($breedRepo->create($breedname))
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