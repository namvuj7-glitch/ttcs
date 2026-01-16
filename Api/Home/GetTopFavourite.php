<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
include __DIR__ . '/../../Includes/Connects.php';

// Lấy top 10 truyện có lượt yêu thích cao nhất
$sql = "
    SELECT StoryID, StoryName, Img, view, Favourite
    FROM story
    ORDER BY Favourite DESC
    LIMIT 10
";

$result = $conn->query($sql);

$favStories = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $favStories[] = [
            'StoryID' => $row['StoryID'],
            'StoryName' => $row['StoryName'],
            'Img' => $row['Img'],
            'view' => $row['view'],
            'Favourite' => $row['Favourite']
        ];
    }
}

// Xuất dữ liệu JSON
echo json_encode($favStories, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
