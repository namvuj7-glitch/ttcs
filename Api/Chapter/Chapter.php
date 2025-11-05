<?php
header("Content-Type: application/json; charset=UTF-8");
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
// Kiểm tra có ChapterID không
if (!isset($_GET['ChapterID'])) {
    echo json_encode(["error" => "Thiếu ChapterID"]);
    exit;
}

$chapterID = intval($_GET['ChapterID']);

// --- Lấy nội dung chương ---
$sql = "SELECT ChapterID, ChapterNumber, Content, StoryID 
        FROM chapter 
        WHERE ChapterID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $chapterID);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storyID = $row['StoryID'];

    // --- Lấy chương trước ---
    $prevSql = "SELECT ChapterID FROM chapter 
                WHERE StoryID = ? AND ChapterID < ? 
                ORDER BY ChapterID DESC LIMIT 1";
    $prevStmt = $conn->prepare($prevSql);
    $prevStmt->bind_param("ii", $storyID, $chapterID);
    $prevStmt->execute();
    $prevResult = $prevStmt->get_result();
    $prevID = ($prevResult->num_rows > 0)
        ? $prevResult->fetch_assoc()['ChapterID']
        : null;

    // --- Lấy chương sau ---
    $nextSql = "SELECT ChapterID FROM chapter 
                WHERE StoryID = ? AND ChapterID > ? 
                ORDER BY ChapterID ASC LIMIT 1";
    $nextStmt = $conn->prepare($nextSql);
    $nextStmt->bind_param("ii", $storyID, $chapterID);
    $nextStmt->execute();
    $nextResult = $nextStmt->get_result();
    $nextID = ($nextResult->num_rows > 0)
        ? $nextResult->fetch_assoc()['ChapterID']
        : null;

    // --- Trả JSON ---
    echo json_encode([
        "ChapterID" => $row['ChapterID'],
        "ChapterNumber" => $row['ChapterNumber'],
        "StoryID" => $row['StoryID'],
        "Content" => $row['Content'],
        "PrevChapterID" => $prevID,
        "NextChapterID" => $nextID
    ], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Không tìm thấy chương."]);
}
?>