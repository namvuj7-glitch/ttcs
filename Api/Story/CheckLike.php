<?php
include '../config.php';
$userID = $_GET['userID'];
$storyID = $_GET['storyID'];

$query = "SELECT * FROM likes WHERE UserID = '$userID' AND StoryID = '$storyID'";
$result = $conn->query($query);

echo json_encode(['liked' => $result->num_rows > 0]);
?>
