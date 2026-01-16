<?php
include __DIR__ . '/../../Includes/Connects.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
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
