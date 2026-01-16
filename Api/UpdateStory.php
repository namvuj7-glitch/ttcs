<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Nhận dữ liệu JSON từ React
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['storyID'] ?? null;
$title = $data['storyName'] ?? '';
$author = $data['author'] ?? '';
$category = $data['category'] ?? '';
$img = $data['img'] ?? '';
$description = $data['descrition'] ?? '';
$publishedDate = $data['PublishedDate'] ?? date("Y-m-d H:i:s");

// Kiểm tra dữ liệu
if (!$id) {
    echo json_encode(["success" => false, "message" => "Thiếu ID truyện"]);
    exit;
}
// --- Hàm lấy hoặc tạo Author ---
function getOrCreateAuthor($conn, $authorName) {
    if (empty($authorName)) return null;

    $sql = "SELECT AuthorID FROM author WHERE AuthorName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $authorName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['AuthorID'];
    } else {
        $sqlInsert = "INSERT INTO author (AuthorName) VALUES (?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("s", $authorName);
        $stmtInsert->execute();
        return $stmtInsert->insert_id;
    }
}

// --- Hàm lấy hoặc tạo Category ---
function getOrCreateCategory($conn, $categoryName) {
    if (empty($categoryName)) return null;

    $sql = "SELECT CategoryID FROM category WHERE CategoryName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $categoryName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['CategoryID'];
    } else {
        $sqlInsert = "INSERT INTO category (CategoryName) VALUES (?)";
        $stmtInsert = $conn->prepare($sqlInsert);
        $stmtInsert->bind_param("s", $categoryName);
        $stmtInsert->execute();
        return $stmtInsert->insert_id;
    }
}

// Lấy AuthorID và CategoryID
$authorId = getOrCreateAuthor($conn, $author);
$categoryId = getOrCreateCategory($conn, $category);

// --- Cập nhật truyện ---
$sql = "UPDATE story 
        SET StoryName = ?, AuthorID = ?, CategoryID = ?, Img = ?, Descrition = ?, PublishedDate = ? 
        WHERE StoryID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siisssi", $title, $authorId, $categoryId, $img, $description, $publishedDate, $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Cập nhật thành công"]);
} else {
    echo json_encode(["success" => false, "message" => "Lỗi khi cập nhật"]);
}
?>
