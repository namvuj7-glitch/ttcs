<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
$userID = isset($_POST['UserID']) ? intval($_POST['UserID']) : 0;
$storyID = isset($_POST['StoryID']) ? intval($_POST['StoryID']) : 0;
$chapterID = isset($_POST['ChapterID']) ? intval($_POST['ChapterID']) : 0;
$sql_check = "SELECT * FROM history 
              WHERE UserID = ? AND StoryID = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $userID, $storyID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $sql_update = "UPDATE history 
                   SET ChapterID = ?
                   WHERE UserID = ? AND StoryID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("iii", $chapterID, $userID, $storyID);
} else {
    $sql_insert = "INSERT INTO history (UserID, StoryID, ChapterID) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iii", $userID, $storyID, $chapterID);
}
?>