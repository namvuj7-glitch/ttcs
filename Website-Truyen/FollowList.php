<link rel="stylesheet" href="Assets/Css/FollowList.css">
<?php
include 'Includes/Connects.php';
include 'Includes/Header.php';
// Kiểm tra user đã đăng nhập
if (!isset($_SESSION['UserID'])) {
    echo "<p>Bạn cần <a href='Login.php'>đăng nhập</a> để xem danh sách theo dõi.</p>";
    include 'Includes/Footer.php';
    exit;
}
$userID = $_SESSION['UserID'];
// Lấy danh sách truyện theo dõi
$sql = "SELECT story.StoryID, story.StoryName, story.Img, author.AuthorName
        FROM follow
        JOIN story ON follow.StoryID = story.StoryID
        LEFT JOIN author ON story.AuthorID = author.AuthorID
        WHERE follow.UserID = ?
        ORDER BY story.StoryName ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
echo "<h2>Danh sách truyện bạn đang theo dõi</h2>";
if ($result->num_rows > 0) {
    echo "<div class='follow-list'>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='follow-item'>";
        echo "<img src='Assets/Img/{$row['Img']}' width='100' alt='{$row['StoryName']}'>";
        echo "<h3><a href='Story.php?StoryID={$row['StoryID']}'>{$row['StoryName']}</a></h3>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>Bạn chưa theo dõi truyện nào.</p>";
}

include 'Includes/Footer.php';
?>
