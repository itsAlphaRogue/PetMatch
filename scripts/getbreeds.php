<?php

include "../includes/database.php";

if($_SERVER["REQUEST_METHOD"] == 'POST')
{

    $inputbreedname = $_POST['breedname'];
    $query = "SELECT * FROM `breeds`";

    if($inputbreedname != '')
    {
        $query .= " WHERE `name` LIKE '%$inputbreedname%'";
    }

    $result = mysqli_query($con, $query);

    if(!empty(mysqli_num_rows($result)))
    {
        echo <<<END
            <table class="w-full border-neutral-400">
                <tr class="h-12">
                    <th class="border-b border-neutral-400">Breed name</th>
                    <th class="border-b border-neutral-400">Actions</th>
                </tr>
        END;
        while($row = mysqli_fetch_assoc($result))
        {
            $name = $row['name'];
            $id = $row['id'];

            echo <<<END
                <tr class="h-12 border-b border-neutral-400">
                    <td class="text-center">
                        <form onsubmit="updateBreed(event,$id)">
                            <input id="editbreed$id" onblur="cancelEdit($id, '$name')" class="text-center capitalize outline-black" disabled type="text" name="breedname" value="$name">
                            <input class="hidden" type="submit">
                        </form>
                    </td>

                    <td class="flex h-12 items-center justify-center gap-8">
                        <button onclick="editBreed($id)" class="h-9 w-full cursor-pointer rounded-sm bg-blue-500 text-center font-semibold transition-colors hover:bg-blue-300 active:bg-blue-600">
                            <p>Edit</p>
                        </button>
                    
                        <button onclick="deleteBreed($id)" class="h-9 w-full cursor-pointer rounded-sm bg-red-500 text-center font-semibold transition-colors hover:bg-red-300 active:bg-red-600">
                            <p>Delete</p>
                        </button>
                    </td>
                </tr>
                END;
            }
        echo "</table>";
    }
    else
    {
        echo <<<END
            <p class="flex w-full items-center justify-center py-32 text-3xl">
                No breed was found. Try different key words.
            </p>
        END;
    }
}