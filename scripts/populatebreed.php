<?php

include '../includes/database.php';

$query = "SELECT * FROM `breeds`";

$result = mysqli_query($con, $query);

while($row = mysqli_fetch_assoc($result))
{
    $name = $row['name'];
    $id = $row['id'];
    echo <<<END
        <option value="$id">$name</option>
    END;
}