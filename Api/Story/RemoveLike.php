<?php
include __DIR__ . '/../../Includes/Connects.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
$data = json_decode(file_get_contents("php://input"), true);
$userID = $data['userID'];
$storyID = $data['storyID'];

$conn->query("DELETE FROM Favourite WHERE UserID='$userID' AND StoryID='$storyID'");
$conn->query("UPDATE story SET Follow = Follow - 1 WHERE StoryID = $storyID");
echo json_encode(['success' => true]);
?>
