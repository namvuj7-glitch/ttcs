<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
include __DIR__ . '/../../Includes/Connects.php';

if (!isset($_GET['CategoryID'])) {
    echo json_encode(["error" => "Thiếu tham số CategoryID"]);
    exit;
}

$categoryID = intval($_GET['CategoryID']); // ép kiểu để tránh lỗi SQL Injection

// Lấy tên thể loại
$categoryQuery = $conn->prepare("SELECT CategoryName FROM category WHERE CategoryID = ?");
$categoryQuery->bind_param("i", $categoryID);
$categoryQuery->execute();
$categoryResult = $categoryQuery->get_result();
$categoryRow = $categoryResult->fetch_assoc();

if (!$categoryRow) {
    echo json_encode(["error" => "Không tìm thấy thể loại"]);
    exit;
}

$categoryName = $categoryRow['CategoryName'];

// Lấy danh sách truyện trong thể loại
$sql = "SELECT story.StoryID, story.StoryName, story.Img
        FROM story
        WHERE story.CategoryID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $categoryID);
$stmt->execute();
$result = $stmt->get_result();

$stories = [];
while ($row = $result->fetch_assoc()) {
    $stories[] = [
        "StoryID" => $row["StoryID"],
        "StoryName" => $row["StoryName"],
        "Img" => $row["Img"],
    ];
}

// Trả kết quả JSON
echo json_encode(   $stories, JSON_UNESCAPED_UNICODE);
?>