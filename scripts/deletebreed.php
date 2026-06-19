<?php

use PetMatch\Repository\BreedRepository;

include "../includes/database.php";

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $breedid = (int)$_POST['id'];

    $breedRepo = new BreedRepository();

    if($breedRepo->hasAssociatedPets($breedid)) 
    {
        echo <<<END
            <p class="fixed right-10 bottom-14 rounded-xl bg-red-400 px-8 py-4 text-center">Can't delete breed. Pet with this breed exists.</p>
        END;
    }
    else
    {
        if($breedRepo->delete($breedid))
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