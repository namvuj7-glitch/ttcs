<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
include __DIR__ . '/../Includes/Connects.php';

$sql = "SELECT StoryID, StoryName, Img FROM Story ORDER BY Favourite DESC LIMIT 5";
$result = $conn->query($sql);

$banners = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $banners[] = $row;
    }
}

echo json_encode($banners);
