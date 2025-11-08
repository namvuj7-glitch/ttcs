<?php
include '../db_connect.php';
header('Content-Type: application/json');

// Nhận dữ liệu JSON từ React
$data = json_decode(file_get_contents("php://input"), true);

$storyName = $data['storyName'] ?? '';
$author = $data['author'] ?? '';
$category = $data['category'] ?? '';
$img = $data['img'] ?? '';
$descrition = $data['descrition'] ?? '';
$publishedDate = date("Y-m-d H:i:s"); // Tự động set ngày đăng hiện tại

// Kiểm tra dữ liệu đầu vào
if (empty($storyName) ) {
    echo json_encode(["success" => false, "message" => "Thiếu thông tin tiêu đề "]);
    exit;
}

// Thêm truyện vào database
$sql = "INSERT INTO story (StoryName, Img, Descrition, PublishedDate)
        VALUES (?, ?, ?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $storyName, $img, $descrition,$publishedDate);
$sql1 ="INSERT INTO author (AuthorName)
VALUES (?)";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("s",$author);
$sql2 ="INSERT INTO category (CategoryName)
VALUES (?)";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("s",$author);
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Thêm truyện thành công", 
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Lỗi khi thêm truyện"]);
}
?>
