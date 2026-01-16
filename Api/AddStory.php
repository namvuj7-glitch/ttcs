<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Nhận dữ liệu JSON từ React
$data = json_decode(file_get_contents("php://input"), true);

$storyName = $data['storyName'] ?? '';
$author = $data['author'] ?? '';
$category = $data['category'] ?? '';
$img = $data['img'] ?? '';
$descrition = $data['descrition'] ?? '';
$publishedDate = date("Y-m-d H:i:s");

// Kiểm tra dữ liệu đầu vào
if (empty($storyName)) {
    echo json_encode(["success" => false, "message" => "Thiếu thông tin tiêu đề"]);
    exit;
}

// --- Thêm author nếu chưa tồn tại ---
$authorId = null;
if (!empty($author)) {
    $sqlCheckAuthor = "SELECT AuthorID FROM author WHERE AuthorName = ?";
    $stmtCheck = $conn->prepare($sqlCheckAuthor);
    $stmtCheck->bind_param("s", $author);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $authorId = $row['AuthorID'];
    } else {
        $sqlInsertAuthor = "INSERT INTO author (AuthorName) VALUES (?)";
        $stmtInsert = $conn->prepare($sqlInsertAuthor);
        $stmtInsert->bind_param("s", $author);
        $stmtInsert->execute();
        $authorId = $stmtInsert->insert_id; // lấy ID mới
    }
}

// --- Thêm category nếu chưa tồn tại ---
$categoryId = null;
if (!empty($category)) {
    $sqlCheckCategory = "SELECT CategoryID FROM category WHERE CategoryName = ?";
    $stmtCheck = $conn->prepare($sqlCheckCategory);
    $stmtCheck->bind_param("s", $category);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $categoryId = $row['CategoryID'];
    } else {
        $sqlInsertCategory = "INSERT INTO category (CategoryName) VALUES (?)";
        $stmtInsert = $conn->prepare($sqlInsertCategory);
        $stmtInsert->bind_param("s", $category);
        $stmtInsert->execute();
        $categoryId = $stmtInsert->insert_id;
    }
}

// --- Thêm truyện ---
$sql = "INSERT INTO story (StoryName, Img, Descrition, PublishedDate, AuthorID, CategoryID)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssii", $storyName, $img, $descrition, $publishedDate, $authorId, $categoryId);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Thêm truyện thành công",
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Lỗi khi thêm truyện"]);
}
?>
