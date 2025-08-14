<?php
include "../includes/database.php";

$id = $_POST['id'];
$name = $_POST['name'];
$breed_id = $_POST['breed'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$description = $_POST['description'];

// First, fetch current image name from DB
$result = mysqli_query($con, "SELECT `image` FROM `pets` WHERE `id` = $id");
$row = mysqli_fetch_assoc($result);
$currentImage = $row['image'];

$imageUpdated = false;

// Check if a new file was uploaded
if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['pet_image']['tmp_name'];
    $fileName = $_FILES['pet_image']['name'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Create unique file name to avoid overwriting accidentally
    $newFileName = uniqid("pet_", true) . "." . strtolower($fileExtension);
    $uploadFolder = "../assets/images/pets/";
    $destPath = $uploadFolder . $newFileName;

    // Move uploaded file to destination folder
    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $imageUpdated = true;

        // Delete old image file if it exists
        $oldImagePath = $uploadFolder . $currentImage;
        if ($currentImage && file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }
}

// Update query (with or without image)
if ($imageUpdated) {
    $query = "UPDATE `pets` 
              SET `name`='$name',
                  `breed_id`=$breed_id,
                  `age`='$age',
                  `gender`='$gender',
                  `description`='$description',
                  `image`='$newFileName'
              WHERE `id`=$id";
} else {
    $query = "UPDATE `pets` 
              SET `name`='$name',
                  `breed_id`=$breed_id,
                  `age`='$age',
                  `gender`='$gender',
                  `description`='$description'
              WHERE `id`=$id";
}

mysqli_query($con, $query);

echo <<<END
    <p class="fixed right-10 bottom-14 rounded-xl bg-green-400 px-8 py-4 text-center">
        Pet updated successfully
    </p>
END;
