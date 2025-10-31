<?php
include '../Includes/Connects.php';
header("Content-Type: application/json");

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$sql = "SELECT story.StoryID, story.StoryName, story.Img, author.AuthorName
        FROM story 
        LEFT JOIN author ON story.AuthorID = author.AuthorID
        WHERE story.StoryName LIKE ? 
           OR (author.AuthorName IS NOT NULL AND author.AuthorName LIKE ?)";

$stmt = $conn->prepare($sql);
$search = "%$keyword%";
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$result = $stmt->get_result();

$stories = [];
while ($row = $result->fetch_assoc()) {
    $stories[] = $row;
}

echo json_encode($stories);
?>
