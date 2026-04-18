<?php

    include '../includes/database.php';

    $inputname = $_POST['name'];
    $inputbreed = $_POST['breed'];
    $inputage = $_POST['age'];
    $inputstatus = $_POST['status'];
    $inputgender = $_POST['gender'];

    $query = "SELECT * FROM pets WHERE 1 = 1";

    if(!empty($inputname))
    {
        $query .= " AND `name` LIKE '%$inputname%'";
    }

    if(!empty($inputbreed))
    {
        $query .= " AND `breed_id` = $inputbreed";
    }

    if(!empty($inputage))
    {
        $query .= " AND `age` = '$inputage'";
    }

    if(!empty($inputstatus))
    {
        $query .= " AND `status` = '$inputstatus'";
    }

    if(!empty($inputgender))
    {
        $query .= " AND `gender` = '$inputgender'";
    }

    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) == 0)
    {
        echo <<<END
        <p class="flex w-full items-center justify-center py-32 text-3xl">
            No pets found. Try different keywords.
        </p>
        END;
    }
    while($row = mysqli_fetch_assoc($result))
    {
        $breed_id = $row['breed_id'];
        $breedquery = "SELECT name FROM breeds WHERE id = $breed_id LIMIT 1";
        $breedresult = mysqli_fetch_assoc(mysqli_query($con, $breedquery));

        $petid = $row['id'];
        $name = $row['name'];
        $image = $row['image'];
        $breed = $breedresult['name'];
        $age = $row['age'];
        $gender = $row['gender'];
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
        }
        elseif($status == "Currently reserved")
        {
            $colors = "bg-yellow-400/30 text-yellow-600";
        }
        else
        {
            $colors = "bg-red-400/30 text-red-600";
        }


        echo <<<END
        <div class="flex items-center gap-2">
            <div class="overflow-hidden w-56 h-44 object-cover object-top">
                <img src="assets/images/pets/$image" alt="" />  
            </div>
            <div class="flex flex-col gap-2">
                <p class="text-2xl font-bold">$name</p>
                <p class="text-neutral-600">$breed</p>
                <p class="text-neutral-600">$age ● $gender</p>
                <div class="flex w-fit items-center justify-center rounded-3xl px-3 $colors">
                    <p class="mr-1 text-2xl">●</p>
                    <p class="font-semibold">$status</p>
                </div>
                <div class="flex gap-2">
                    <a href="pet?id=$petid" target="_blank">
                        <span class="flex w-24 justify-center rounded-md bg-green-500 px-2 py-2 cursor-pointer transition-colors hover:bg-green-300 active:bg-green-600">
                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z" /></svg>
                            View
                        </span>
                    </a>

                    <a href="editpet?id=$petid" target="_blank">
                        <span class="flex w-24 justify-center rounded-md bg-blue-500 px-2 py-2 cursor-pointer transition-colors hover:bg-blue-300 active:bg-blue-600">
                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" /></svg>
                            Edit
                        </span>
                    </a>
                </div>
            </div>
        </div>
        END;


    }
