<?php
session_start();
include 'Includes/Connects.php';

if (!isset($_SESSION['UserID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$storyID = $_POST['StoryID'] ?? 0;
$action = $_POST['action'] ?? ''; // 'follow' hoặc 'unfollow'
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