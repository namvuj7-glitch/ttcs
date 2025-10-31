<?php
include 'Includes/Connects.php';

$storyID = $_POST['StoryID'] ?? 0;
$conn->query("UPDATE story SET view = view + 1 WHERE StoryID = $storyID");
echo json_encode(['success' => true]);
?>