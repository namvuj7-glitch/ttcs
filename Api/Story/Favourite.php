<?php
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // ✅ Kiểm tra user đã like chưa
    $userID = intval($_GET['userID']);
    $storyID = intval($_GET['storyID']);

    $sql = "SELECT * FROM favourite WHERE UserID = $userID AND StoryID = $storyID";
    $result = $conn->query($sql);
    echo json_encode(["liked" => $result->num_rows > 0]);
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $userID = intval($data['userID']);
    $storyID = intval($data['storyID']);
    $action = $data['action']; // "like" hoặc "unlike"

    if ($action === 'like') {
        // Thêm vào bảng favourite
        $conn->query("INSERT IGNORE INTO favourite (UserID, StoryID) VALUES ($userID, $storyID)");
        // Tăng cột Favourite trong story
        $conn->query("UPDATE story SET Favourite = Favourite + 1 WHERE StoryID = $storyID");
        echo json_encode(["message" => "Liked"]);
    }

    if ($action === 'unlike') {
        // Xóa khỏi bảng favourite
        $conn->query("DELETE FROM favourite WHERE UserID = $userID AND StoryID = $storyID");
        // Giảm cột Favourite trong story (chặn âm)
        $conn->query("UPDATE story SET Favourite = GREATEST(Favourite - 1, 0) WHERE StoryID = $storyID");
        echo json_encode(["message" => "Unliked"]);
    }
}
?>
