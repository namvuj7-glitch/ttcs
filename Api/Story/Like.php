<?php
session_start();
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
if (!isset($_SESSION['UserID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$storyID = $_POST['StoryID'] ?? 0;
$action = $_POST['action'] ?? ''; 
$userID = $_SESSION['UserID'];

if ($action === 'like') {
    $conn->query("INSERT IGNORE INTO Favourite (StoryID, UserID) VALUES ($storyID, $userID)");
    $conn->query("UPDATE story SET Favourite = Favourite+ 1 WHERE StoryID = $storyID");
} elseif ($action === 'unlike') {
    $conn->query("DELETE FROM favourite WHERE StoryID = $storyID AND UserID = $userID");
    $conn->query("UPDATE story SET Favourite = GREATEST(Favourite - 1, 0) WHERE StoryID = $storyID");
}

echo json_encode(['success' => true]);
?>