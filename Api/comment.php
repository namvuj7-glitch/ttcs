<?php
include __DIR__ . '/../Includes/Connects.php'; 
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $storyID = isset($_GET['storyID']) ? intval($_GET['storyID']) : 0;

    $sql = "SELECT c.CommentID, c.StoryID, c.UserID, c.Contentc, c.CreatAT, u.Username 
            FROM comment c 
            JOIN user u ON c.UserID = u.UserID 
            WHERE c.StoryID = $storyID 
            ORDER BY c.CreatAT DESC";
    $result = $conn->query($sql);

    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    echo json_encode($comments);
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $storyID = intval($data['storyID']);
    $userID = intval($data['userID']);
    $content = $conn->real_escape_string($data['content']);

    $sql = "INSERT INTO comment (StoryID, UserID, Contentc) VALUES ('$storyID', '$userID', '$content')";
    if ($conn->query($sql)) {
        echo json_encode(["message" => "Comment added successfully"]);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}
?>
