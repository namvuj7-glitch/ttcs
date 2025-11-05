<?php
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Đọc dữ liệu JSON từ body request
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra có StoryID không
$storyID = $data['StoryID'] ?? 0;

if ($storyID > 0) {
    $stmt = $conn->prepare("UPDATE story SET view = view + 1 WHERE StoryID = ?");
    $stmt->bind_param("i", $storyID);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Không thể cập nhật view']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Thiếu StoryID']);
}
?>
