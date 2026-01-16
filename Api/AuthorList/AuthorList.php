<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
include __DIR__ . '/../../Includes/Connects.php';
session_start();

// Câu lệnh SQL lấy tất cả tác giả
$sql = "SELECT * FROM author";
$result = $conn->query($sql);
$authors = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $authors[] = [
            "AuthorID" => $row["AuthorID"],
            "AuthorName" => $row["AuthorName"],
        ];
    }
}

// Trả dữ liệu JSON
echo json_encode($authors, JSON_UNESCAPED_UNICODE);
?>