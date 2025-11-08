<?php
session_start();
include __DIR__ . '/../../Includes/Connects.php';
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if (!isset($_SESSION['UserID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$storyID = $_POST['StoryID'] ?? 0;
$action = $_POST['action'] ?? ''; 
$userID = $_SESSION['UserID'];

if ($action === 'follow') {
    $conn->query("INSERT IGNORE INTO follow (StoryID, UserID) VALUES ($storyID, $userID)");
    $conn->query("UPDATE story SET Follow = Follow + 1 WHERE StoryID = $storyID");
} elseif ($action === 'unfollow') {
    $conn->query("DELETE FROM follow WHERE StoryID = $storyID AND UserID = $userID");
    $conn->query("UPDATE story SET Follow = GREATEST(Follow - 1, 0) WHERE StoryID = $storyID");
}

echo json_encode(['success' => true]);
?>