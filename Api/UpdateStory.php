<?php
include '../db_connect.php';
header('Content-Type: application/json');

// Nhận dữ liệu JSON từ React
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$title = $data['title'] ?? '';
$author = $data['author'] ?? '';
$status = $data['status'] ?? '';
$description = $data['description'] ?? '';
$publishedDate = $data['PublishedDate'] ?? date("Y-m-d H:i:s"); // nếu bạn muốn cập nhật lại ngày đăng

// Kiểm tra dữ liệu
if (!$id) {
    echo json_encode(["success" => false, "message" => "Thiếu ID truyện"]);
    exit;
}

// Cập nhật thông tin truyện
$sql = "UPDATE stories 
        SET title = ?, author = ?, status = ?, description = ?, PublishedDate = ? 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $title, $author, $status, $description, $publishedDate, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Cập nhật thành công"]);
} else {
    echo json_encode(["success" => false, "message" => "Lỗi khi cập nhật"]);
}
?>
