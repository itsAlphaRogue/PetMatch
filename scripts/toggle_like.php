<?php
include '../includes/database.php';
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in to like stories.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['id'];
    $story_id = intval($_POST['story_id'] ?? 0);

    if ($story_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid story ID.']);
        exit;
    }

    // Check if already liked
    $check_sql = "SELECT id FROM story_likes WHERE story_id = ? AND user_id = ?";
    $check_stmt = mysqli_prepare($con, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "ii", $story_id, $user_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        // Unlike
        $delete_sql = "DELETE FROM story_likes WHERE story_id = ? AND user_id = ?";
        $delete_stmt = mysqli_prepare($con, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "ii", $story_id, $user_id);
        mysqli_stmt_execute($delete_stmt);
        $liked = false;
    } else {
        // Like
        $insert_sql = "INSERT INTO story_likes (story_id, user_id) VALUES (?, ?)";
        $insert_stmt = mysqli_prepare($con, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "ii", $story_id, $user_id);
        mysqli_stmt_execute($insert_stmt);
        $liked = true;
    }

    // Get updated count
    $count_sql = "SELECT COUNT(*) as count FROM story_likes WHERE story_id = ?";
    $count_stmt = mysqli_prepare($con, $count_sql);
    mysqli_stmt_bind_param($count_stmt, "i", $story_id);
    mysqli_stmt_execute($count_stmt);
    $count_result = mysqli_stmt_get_result($count_stmt);
    $count_row = mysqli_fetch_assoc($count_result);

    echo json_encode([
        'status' => 'success',
        'liked' => $liked,
        'likes_count' => $count_row['count']
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
exit;
