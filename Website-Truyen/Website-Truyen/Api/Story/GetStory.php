<?php
include 'Includes/Connects.php';

$storyID = $_GET['StoryID'] ?? 0;

$sql = "SELECT story.*, author.AuthorName, category.CategoryName 
        FROM story
        LEFT JOIN author ON story.AuthorID = author.AuthorID
        LEFT JOIN category ON story.CategoryID = category.CategoryID
        WHERE story.StoryID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $storyID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    echo json_encode($row);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Story not found']);
}
?>