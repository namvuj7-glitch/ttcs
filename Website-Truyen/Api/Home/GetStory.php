<?php
include '../Includes/Connects.php';
header("Content-Type: application/json");

$id = isset($_GET['StoryID']) ? intval($_GET['StoryID']) : 0;

$sql = "SELECT story.*, author.AuthorName, category.CategoryName
        FROM story
        LEFT JOIN author ON story.AuthorID = author.AuthorID
        LEFT JOIN category ON story.CategoryID = category.CategoryID
        WHERE story.StoryID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode($result->fetch_assoc());
?>
