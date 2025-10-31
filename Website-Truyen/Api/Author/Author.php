<?php
header("Content-Type: application/json; charset=UTF-8");
include '../Includes/Connects.php'; // Đường dẫn tới file kết nối DB
session_start();

// Lấy ID tác giả từ query string
$authorID = isset($_GET['AuthorID']) ? intval($_GET['AuthorID']) : 0;

// Kiểm tra ID hợp lệ
if ($authorID <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Thiếu hoặc sai AuthorID."]);
    exit;
}

// Lấy thông tin tác giả
$sqlAuthor = "SELECT * FROM author WHERE AuthorID = ?";
$stmt = $conn->prepare($sqlAuthor);
$stmt->bind_param("i", $authorID);
$stmt->execute();
$resultAuthor = $stmt->get_result();
$author = $resultAuthor->fetch_assoc();

if (!$author) {
    http_response_code(404);
    echo json_encode(["error" => "Không tìm thấy tác giả này."]);
    exit;
}

// Lấy danh sách truyện của tác giả này
$sqlStory = "SELECT StoryID, StoryName, Descrition, Img 
             FROM story 
             WHERE AuthorID = ?";
$stmt = $conn->prepare($sqlStory);
$stmt->bind_param("i", $authorID);
$stmt->execute();
$resultStory = $stmt->get_result();

$stories = [];
while ($row = $resultStory->fetch_assoc()) {
    $stories[] = [
        "StoryID" => $row["StoryID"],
        "StoryName" => $row["StoryName"],
        "Descrition" => $row["Descrition"],
        "Img" => $row["Img"]
    ];
}

// Trả dữ liệu JSON
$response = [
    "AuthorID" => $author["AuthorID"],
    "AuthorName" => $author["AuthorName"],
    "Bio" => $author["Bio"] ?? null, // nếu có cột mô tả tác giả
    "Stories" => $stories
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>