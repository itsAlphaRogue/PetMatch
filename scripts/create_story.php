<?php
include '../includes/database.php';
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to share a story.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $pet_name = trim($_POST['pet_name'] ?? '');
    $story_text = trim($_POST['story_text'] ?? '');
    
    if (empty($pet_name) || empty($story_text) || !isset($_FILES['story_image'])) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all fields and upload an image.']);
        exit;
    }

    $file = $_FILES['story_image'];
    
    // Check for PHP upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
            UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
        ];
        $err_msg = $upload_errors[$file['error']] ?? 'Unknown upload error.';
        echo json_encode(['status' => 'error', 'message' => 'File upload error: ' . $err_msg]);
        exit;
    }

    // Image Validation
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    $max_size = 5 * 1024 * 1024; // 5MB

    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $file_size = $file['size'];
    $tmp_path = $file['tmp_name'];

    if (!in_array($file_ext, $allowed_extensions)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid file extension. Allowed: jpg, png, webp.']);
        exit;
    }

    if ($file_size > $max_size) {
        echo json_encode(['status' => 'error', 'message' => 'File size exceeds 5MB limit.']);
        exit;
    }

    // MIME type check
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $tmp_path);
    finfo_close($finfo);

    $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mime_type, $allowed_mimes)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid image file.']);
        exit;
    }

    // Sanitize filename
    $new_filename = uniqid('story_', true) . '.' . $file_ext;
    $upload_dir = __DIR__ . '/../assets/images/stories/';
    
    // Ensure directory exists (fallback)
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $upload_path = $upload_dir . $new_filename;
    $db_path = 'assets/images/stories/' . $new_filename;

    if (move_uploaded_file($tmp_path, $upload_path)) {
        $sql = "INSERT INTO success_stories (user_id, pet_name, story_text, image_path) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $pet_name, $story_text, $db_path);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Story shared successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($con)]);
        }
    } else {
        $error = error_get_last();
        $msg = 'Failed to upload image.';
        if ($error) {
            $msg .= ' ' . $error['message'];
        }
        if (!is_writable($upload_dir)) {
            $msg .= ' Upload directory is not writable. Please check permissions for assets/images/stories/';
        }
        echo json_encode(['status' => 'error', 'message' => $msg]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
exit;
