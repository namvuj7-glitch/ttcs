<?php
include __DIR__ .'/../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
$data = json_decode(file_get_contents("php://input"), true);
$userID = $data["userID"] ?? 0;
$sql="SELECT history.StoryID,story.StoryName,history.ChapterID,chapter.ChapterNumber, story.Img
      FROM history
      LEFT JOIN story ON story.StoryID=history.StoryID
      LEFT JOIN chapter ON chapter.ChapterID=history.ChapterID
      WHERE UserID=$userID";
$result=$conn->query($sql);
$history=[];
while($row=$result->fetch_assoc()){
    $history[]=$row;
}
echo json_encode($history)
?>