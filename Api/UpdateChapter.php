<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);
$chapterID = $data['chapterID'];
$chapterNumber = $data['chapterNumber'];
$content = $data['content'];

$sql = "UPDATE chapter 
        SET ChapterNumber='$chapterNumber', Content='$content' 
        WHERE ChapterID='$chapterID'";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true, "message" => "Cập nhật thành công"]);
} else {
    echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
}
?>
