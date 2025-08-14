<?php

// Note: this php scripts handles displaying pets in both home and findapet page.
// In home page display only 4 pets using LIMIT

include "../includes/database.php";

$query = "SELECT * FROM `pets` ";

$data = json_decode(file_get_contents("php://input"), true);

if(!empty($data))
{
    $limit = $data['limit'];
    $query .= "WHERE `status`='Available' LIMIT $limit";
}


$result = mysqli_query($con, $query);

while($row = mysqli_fetch_assoc($result))
{
    $id = $row['id'];
    $image = $row['image'];
    $name = $row['name'];
    $age = $row['age'];
    $gender = $row['gender'];
    
    $breedid =$row['breed_id'];
    $breedquery = "SELECT `name` FROM `breeds` WHERE `id` = '$breedid'";
    $breedresult = mysqli_fetch_assoc(mysqli_query($con, $breedquery));
    $breed = $breedresult['name'];

    echo <<<END
    <a href="pet?id=$id">
        <div class="card-shadow max-w-xs overflow-hidden rounded-3xl">
            <div class="h-64 overflow-hidden">
                <img class="w-full" src="assets/images/pets/$image" alt="" />
            </div>
            <div class="mx-4 my-4 flex flex-col gap-2">
                <p class="text-2xl font-bold">$name</p>
                <p class="text-neutral-600">$breed</p>
                <p class="text-neutral-600">$age ● $gender</p>
            </div>
        </div>
    </a>
    END;
}