<?php
header("Access-Control-Allow-Origin: *"); // Cho phép React gọi API
header("Content-Type: application/json; charset=UTF-8");
header("Content-Type: application/json");
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
