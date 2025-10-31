<?php
session_start();
include 'Includes/Connects.php';

if (!isset($_SESSION['UserID'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$storyID = $_POST['StoryID'] ?? 0;
$content = $_POST['content'] ?? '';
$userID = $_SESSION['UserID'];

if ($content !== '') {
    $stmt = $conn->prepare("INSERT INTO comment (StoryID, UserID, Contentc) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $storyID, $userID, $content);
    $stmt->execute();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Empty content']);
}
?>