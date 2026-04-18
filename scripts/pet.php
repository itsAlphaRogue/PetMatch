<?php

include "../includes/database.php";
$data = json_decode(file_get_contents("php://input"), true);


$petid = $data['id'] ?? null;
$query = "SELECT * FROM `pets` WHERE `id` = $petid";

$petresult = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($petresult);
if(mysqli_num_rows($petresult) == 0)
{
    echo <<<END
        <div class="w-full flex justify-center items-center">
            <p class="text-2xl">Pet you searched was not found</p>
        </div> 
    END;
    exit;
}

$breed_id = $row['breed_id'];
$breedquery = "SELECT name FROM breeds WHERE id = $breed_id LIMIT 1";
$breedresult = mysqli_fetch_assoc(mysqli_query($con, $breedquery));


$image = $row['image'];
$breed = $breedresult['name'];
$name = $row['name'];
$age = $row['age'];
$gender = $row['gender'];
$description = $row['description'];

// status in database and status to display are different so we need to do this
if($row['status'] == "Available")
{
    $status = "Available for adoption";
}
elseif($row['status'] == "Reserved")
{
    $status = "Currently reserved";
}
else
{
    $status = "Adopted";
}

// for changing status bg color and text color. 
// checking status and adding color class accordingly.
if($status == "Available for adoption")
{
    $colors = "bg-green-400/80 text-green-900";
    $reserve = "bg-green-400 hover:bg-green-300 active:bg-green-600 cursor-pointer";
}
elseif($status == "Currently reserved")
{
    $colors = "bg-yellow-400/30 text-yellow-600";
    $reserve = "bg-neutral-400 cursor-not-allowed pointer-events-none";
}
else
{
    $colors = "bg-red-400/30 text-red-600";
    $reserve = "bg-neutral-400 cursor-not-allowed pointer-events-none";
}

echo <<<END
    <div class="m-4 overflow-hidden rounded-lg lg:relative lg:m-0 lg:flex-[1] lg:rounded-none">
        <img class="aspect-square rounded-lg object-cover lg:absolute lg:h-full lg:rounded-none" src="assets/images/pets/$image" alt="" />
    </div>

    <div class="m-4 flex flex-col justify-center gap-8 lg:my-0 lg:flex-[2]">
        <div class="flex flex-col gap-4">
            <p class="font-poppins text-3xl font-bold">$name</p>
            <p class="text-neutral-600 capitalize text-xl">$breed</p>
            <p class="text-neutral-600 text-xl">$age ● $gender</p>
            <p class="text-justify">$description</p>
        </div>

        <div class="flex w-fit items-center justify-center rounded-3xl $colors px-3 py-1 text-green-900">
            <p class="mr-1 text-2xl">●</p>
            <p class="font-semibold">$status</p>
        </div>

        <div class="flex items-center justify-between gap-8">
            <div onclick="reservePet($petid)" class="h-fit rounded-md $reserve px-6 py-4">
                <p class="font-semibold">Reserve</p>
            </div>
            <div class="h-fit rounded-md bg-blue-600/30">
                <p class="m-2 font-semibold text-blue-500/95 italic">Please note: If you do not claim your reserved pet within three days of reservation, we may make them available for adoption again.</p>
            </div>
        </div>
    </div>
END;