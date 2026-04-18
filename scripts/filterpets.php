<?php

include '../includes/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $inputage = $_POST['age'];
    $inputgender = $_POST['gender'];
    $inputbreed = $_POST['breed'];
    
    $query = "SELECT * FROM pets WHERE 1 = 1";
    
    
    if(!empty($inputage))
    {
        $query .= " AND `age` = '$inputage'";
    }
    
    if(!empty($inputgender))
    {
        $query .= " AND `gender` = '$inputgender'";
    }
    
    if(!empty($inputbreed))
    {
        $query .= " AND `breed_id` = $inputbreed";
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
    else
    {
        while($row = mysqli_fetch_assoc($result))
        {
            $breed_id = $row['breed_id'];
            $breedquery = "SELECT name FROM breeds WHERE id = $breed_id LIMIT 1";
            $breedresult = mysqli_fetch_assoc(mysqli_query($con, $breedquery));

            $id = $row['id'];
            $name = $row['name'];
            $image = $row['image'];
            $breed = $breedresult['name'];
            $age = $row['age'];
            $gender = $row['gender'];


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
    }
}