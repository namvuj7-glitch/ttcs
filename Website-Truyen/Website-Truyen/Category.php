<link rel="stylesheet" href="Assets/Css/Category.css">
<?php
include 'Includes/Connects.php';
include 'Includes/Header.php';

$categoryID = $_GET['CategoryID']; // lấy id thể loại từ URL

// Lấy tên thể loại
$categoryResult = $conn->query("SELECT CategoryName FROM category WHERE CategoryID = $categoryID");
$categoryRow = $categoryResult->fetch_assoc();
echo "<h2>Thể loại: {$categoryRow['CategoryName']}</h2>";

// Lấy danh sách truyện theo thể loại
$sql = "SELECT story.StoryID, story.StoryName, story.Img
        FROM story
        WHERE story.CategoryID = $categoryID";

$result = $conn->query($sql);

// Hiển thị danh sách truyện trong thể loại này
while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<img src='Assets/Img/{$row['Img']}' width='100'><br>";
    echo "<a href='Story.php?StoryID={$row['StoryID']}'><b>{$row['StoryName']}</b></a><br>"; 
    echo "</div><hr>";
}

include 'Includes/Footer.php';
?>
