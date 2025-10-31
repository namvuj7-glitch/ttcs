<?php
session_start();
include 'Includes/Connects.php';

if (!isset($_SESSION['UserID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$storyID = $_POST['StoryID'] ?? 0;
$action = $_POST['action'] ?? ''; 
$userID = $_SESSION['UserID'];

if ($action === 'like') {
    $conn->query("UPDATE story SET Favourite = Favourite + 1 WHERE StoryID = $storyID");
} elseif ($action === 'unlike') {
    $conn->query("UPDATE story SET Favourite = GREATEST(Favourite - 1, 0) WHERE StoryID = $storyID");
}

echo json_encode(['success' => true]);
?>