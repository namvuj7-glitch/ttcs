<?php
include __DIR__ . '/../Includes/Connects.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$sql = "SELECT story.StoryID,story.StoryName, story.Img,author.AuthorName,category.CategoryName, story.Descrition        
        FROM story
        LEFT JOIN author ON author.AuthorID=story.AuthorID
        LEFT JOIN category ON category.CategoryID=story.CategoryID
        ORDER BY story.PublishedDate DESC";        
$result = $conn->query($sql);
$stories = [];
while ($row = $result->fetch_assoc()) {
$chapters = [];
$storyID=$row['StoryID'];
$sql1 = " SELECT chapter.ChapterNumber,chapter.Content,chapter.ChapterID
FROM chapter
WHERE chapter.StoryID=$storyID
ORDER BY chapter.PublishedDate DESC";
$result1 = $conn->query($sql1);
while ($row1 = $result1->fetch_assoc()) {
    $chapters[] = $row1;
}
    $row['chapters'] = $chapters;
    $stories[] = $row;

}

echo json_encode($stories);