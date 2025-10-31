<?php
include 'Includes/Connects.php';

$storyID = $_GET['StoryID'] ?? 0;
$sql = "SELECT comment.Contentc, comment.CreatAt, user.UserName 
        FROM comment 
        JOIN user ON comment.UserID = user.UserID
        WHERE comment.StoryID = ?
        ORDER BY comment.CreatAt DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $storyID);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
echo json_encode($comments);
?>