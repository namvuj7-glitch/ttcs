<?php
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
$data = json_decode(file_get_contents("php://input"), true);
$userID = $data["userID"] ?? 0;
$chapterID = $data["chapterID"] ?? 0;
$storyID = $data["storyID"] ?? 0;
$sql = "INSERT INTO history (UserID, StoryID, ChapterID)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE ChapterID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $userID, $storyID, $chapterID, $chapterID);
$stmt->execute();
echo json_encode(["message" => "History updated successfully"]);
?>