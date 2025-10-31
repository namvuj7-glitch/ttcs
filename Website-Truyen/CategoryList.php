<link rel="stylesheet" href="Assets/Css/CategoryList.css">
<?php
include 'Includes/Connects.php';
include 'Includes/Header.php';

echo "<h2>Danh sách thể loại</h2>";

// Lấy tất cả thể loại
$sql = "SELECT * FROM category";
$result = $conn->query($sql);

// Hiển thị danh sách
while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "<a href='Category.php?CategoryID={$row['CategoryID']}'><b>{$row['CategoryName']}</b></a>";
    echo "</div><hr>";
}

include 'Includes/Footer.php';
?>
