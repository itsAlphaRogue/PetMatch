<?php
include '../includes/database.php';
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_user_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

$sql = "SELECT 
            s.id, 
            s.pet_name, 
            s.story_text, 
            s.image_path, 
            s.created_at, 
            u.name as author_name,
            (SELECT COUNT(*) FROM story_likes WHERE story_id = s.id) as likes_count,
            (SELECT COUNT(*) FROM story_likes WHERE story_id = s.id AND user_id = ?) as is_liked_by_me
        FROM success_stories s
        JOIN users u ON s.user_id = u.id
        ORDER BY s.created_at DESC";

$stmt = mysqli_prepare($con, $sql);
mysqli_stmt_bind_param($stmt, "i", $current_user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$stories = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row['is_liked_by_me'] = $row['is_liked_by_me'] > 0;
    $stories[] = $row;
}

echo json_encode($stories);
exit;
