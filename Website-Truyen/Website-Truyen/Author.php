<link rel="stylesheet" href="Assets/Css/Author.css">
<?php
include 'Includes/Connects.php';
include 'Includes/Header.php';
// Lấy id tác giả từ URL
$authorID = $_GET['AuthorID'];

// Lấy thông tin tác giả
$sqlAuthor = "SELECT * FROM author WHERE AuthorID = $authorID";
$resultAuthor = $conn->query($sqlAuthor);
$author = $resultAuthor->fetch_assoc();

if (!$author) {
    echo "Không tìm thấy tác giả này.";
    include 'Includes/Footer.php';
    exit;
}

// Hiển thị thông tin tác giả
echo "<h2>Tác giả: {$author['AuthorName']}</h2>";
echo "<hr>";
// Lấy danh sách truyện của tác giả này
$sqlStory = "SELECT story.StoryID, story.StoryName,story.Img
             FROM story
             WHERE story.AuthorID = $authorID";

$resultStory = $conn->query($sqlStory);

// Hiển thị danh sách truyện
if ($resultStory->num_rows > 0) {
    echo "<h3>Các truyện của tác giả này:</h3>";
    while ($row = $resultStory->fetch_assoc()) {
        echo "<div>";
    echo "<img src='Assets/Img/{$row['Img']}' width='100'><br>";
    echo "<a href='Story.php?StoryID={$row['StoryID']}'><b>{$row['StoryName']}</b></a><br>";
    echo "</div><hr>";
    }
} else {
    echo "Tác giả này chưa có truyện nào.";
}

include 'Includes/Footer.php';
?>
