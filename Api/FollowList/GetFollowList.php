<?php
session_start();
include __DIR__ . '/../../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
// Kiểm tra đăng nhập
if (!isset($_SESSION['UserID'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'Bạn cần đăng nhập để xem danh sách theo dõi.']);
    exit;
}

$userID = $_SESSION['UserID'];

// Truy vấn danh sách truyện theo dõi
$sql = "SELECT story.StoryID, story.StoryName, story.Img, author.AuthorName
        FROM follow
        LEFT JOIN story ON follow.StoryID = story.StoryID
        LEFT JOIN user ON follow.UserID = user.UserID
        WHERE follow.UserID = ?
        ORDER BY story.StoryName ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$followList = [];
while ($row = $result->fetch_assoc()) {
    $followList[] = [
        'StoryID' => $row['StoryID'],
        'StoryName' => $row['StoryName'],
        'Img' => $row['Img'],
        'AuthorName' => $row['AuthorName']
    ];
}

// Trả về JSON
if (count($followList) > 0) {
    echo json_encode($followList, JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(['message' => 'Bạn chưa theo dõi truyện nào.']);
}
?>