<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"), true);
$storyID = $data['storyID'];
$chapterNumber = $data['chapterNumber'];
$content = $data['content'];

$publishedDate = date('Y-m-d H:i:s');

$sql = "INSERT INTO chapter (StoryID, ChapterNumber, Content, PublishedDate)
        VALUES ('$storyID', '$chapterNumber', '$content', '$publishedDate')";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true, "message" => "Thêm chapter thành công"]);
} else {
    echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
}
?>
