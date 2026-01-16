<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
$data = json_decode(file_get_contents("php://input"), true);
$userID = $data["userID"] ?? 0;
$sql= "SELECT story.StoryID, story.StoryName, story.Img
       FROM follow
       LEFT JOIN story
       ON story.StoryID=follow.StoryID
       WHERE follow.UserID='$userID'";
$result= $conn->query($sql);
$follow=[];
while($row=$result->fetch_assoc()){
    $follow[]=$row;
}
echo json_encode($follow);
?>