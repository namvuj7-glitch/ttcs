<?php
include '../Includes/Connects.php';
header("Content-Type: application/json");

$sql = "SELECT story.StoryID, story.StoryName, story.Descrition, story.Img, 
               story.PublishedDate, story.View, story.Favourite, story.Follow,
               author.AuthorName, category.CategoryName
        FROM story
        LEFT JOIN author ON story.AuthorID = author.AuthorID
        LEFT JOIN category ON story.CategoryID = category.CategoryID
        ORDER BY story.PublishedDate DESC";
$result = $conn->query($sql);

$stories = [];
while ($row = $result->fetch_assoc()) {
    $stories[] = $row;
}

echo json_encode($stories);
?>
