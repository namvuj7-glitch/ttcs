<?php
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"), true);
$chapterID = $data['chapterID'] ?? 0;

if ($chapterID > 0) {
    $sql = "UPDATE chapter SET view = view + 1 WHERE ChapterID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $chapterID);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $stmt->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid ChapterID"]);
}
?>
