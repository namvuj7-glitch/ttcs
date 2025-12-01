<?php
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"), true);
$storyID = $data['StoryID'];
$userID = $data['UserID'];
$newRating = $data['Rating'];

// Kiểm tra user đã rating chưa
$check = $conn->prepare("SELECT * FROM review WHERE StoryID=? AND UserID=?");
$check->bind_param("ii", $storyID, $userID);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    echo json_encode(["message" => "User đã đánh giá rồi", "success" => false]);
    exit;
}

// Lấy rating hiện tại
$q = $conn->prepare("SELECT Rating FROM story WHERE StoryID=?");
$q->bind_param("i", $storyID);
$q->execute();
$r = $q->get_result()->fetch_assoc();
$oldRating = $r['Rating'];

// Tính rating mới
$updatedRating = ($oldRating + $newRating) / 2;

// Cập nhật vào bảng story
$update = $conn->prepare("UPDATE story SET Rating=? WHERE StoryID=?");
$update->bind_param("di", $updatedRating, $storyID);
$update->execute();

// Thêm vào bảng review
$insert = $conn->prepare("INSERT INTO review (StoryID, UserID) VALUES (?, ?)");
$insert->bind_param("ii", $storyID, $userID);
$insert->execute();

echo json_encode([
    "message" => "Đánh giá thành công!",
    "success" => true,
    "newRating" => $updatedRating
]);
?>
