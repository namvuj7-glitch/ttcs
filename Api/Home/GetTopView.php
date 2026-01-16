<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include __DIR__ . '/../../Includes/Connects.php'; // đường dẫn tới file kết nối database

// Kiểm tra kết nối
if (!isset($conn) || $conn->connect_errno) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu']);
    exit;
}

// Lấy giới hạn (limit) — mặc định là 10
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
if ($limit <= 0) $limit = 10;

// Lấy top truyện có lượt xem cao nhất
$sql = "SELECT StoryID, StoryName, view, Img
        FROM story
        ORDER BY view DESC
        LIMIT ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $limit);
$stmt->execute();
$result = $stmt->get_result();

$stories = [];
while ($row = $result->fetch_assoc()) {
    $stories[] = $row;
}

echo json_encode($stories, JSON_UNESCAPED_UNICODE);

$stmt->close();
$conn->close();
