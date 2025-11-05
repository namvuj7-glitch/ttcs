<?php
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST");
$storyID = $_GET['StoryID'] ?? 0;
$sql = "SELECT ChapterID,PublishedDate,ChapterNumber,StoryID,view FROM chapter WHERE StoryID = ? ORDER BY ChapterNumber ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $storyID);
$stmt->execute();
$result = $stmt->get_result();

$chapters = [];
while ($row = $result->fetch_assoc()) {
    $chapters[] = $row;
}
echo json_encode( $chapters);
?>