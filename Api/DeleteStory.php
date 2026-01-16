<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Đọc dữ liệu gửi từ React
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(["success" => false, "message" => "Thiếu ID truyện"]);
    exit;
}
// Xóa các chương của truyện trước (nếu có)
$deleteChapters = $conn->prepare("DELETE FROM chapter WHERE StoryID = ?");
$deleteChapters->bind_param("i", $id);
$deleteChapters->execute();

// Xóa truyện chính
$deleteStory = $conn->prepare("DELETE FROM story WHERE StoryID = ?");
$deleteStory->bind_param("i", $id);

if ($deleteStory->execute()) {
    echo json_encode(["success" => true, "message" => "Đã xóa truyện thành công"]);
} else {
    echo json_encode(["success" => false, "message" => "Lỗi khi xóa truyện"]);
}
?>
