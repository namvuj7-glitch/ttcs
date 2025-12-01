<?php
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
$storyID = $_GET['storyID'];
$userID = $_GET['userID'];

$q = $conn->prepare("SELECT * FROM review WHERE StoryID=? AND UserID=?");
$q->bind_param("ii", $storyID, $userID);
$q->execute();
$res = $q->get_result();

if ($res->num_rows > 0) {
    echo json_encode(["rated" => true]);
} else {
    echo json_encode(["rated" => false]);
}
?>
