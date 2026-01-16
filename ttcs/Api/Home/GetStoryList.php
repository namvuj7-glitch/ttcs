<?php
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
$sql = "SELECT story.StoryID, story.StoryName, story.Img, story.PublishedDate ,story.view         
        FROM story
        ORDER BY story.PublishedDate DESC";
$result = $conn->query($sql);
$stories = [];
while ($row = $result->fetch_assoc()) {
    $stories[] = $row;
}
echo json_encode($stories);
?>
