<?php
include 'Includes/Connects.php';

$storyID = $_GET['StoryID'] ?? 0;
$sql = "SELECT * FROM chapter WHERE StoryID = ? ORDER BY ChapterNumber ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $storyID);
$stmt->execute();
$result = $stmt->get_result();

$chapters = [];
while ($row = $result->fetch_assoc()) {
    $chapters[] = $row;
}
echo json_encode($chapters);
?>