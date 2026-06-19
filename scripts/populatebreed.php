<?php

use PetMatch\Repository\BreedRepository;

include '../includes/database.php';

$breedRepo = new BreedRepository();
$breeds = $breedRepo->getAll();

foreach ($breeds as $row) {
    $name = $row['name'];
    $id = $row['id'];
    echo <<<END
        <option value="$id">$name</option>
    END;
}