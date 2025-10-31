<link rel="stylesheet" href="Assets/Css/AuthorList.css">
<?php
include 'Includes/Connects.php';
include 'Includes/Header.php';

echo "<h2>Danh sách tác giả</h2>";

// Lấy danh sách tác giả
$sql = "SELECT * FROM author";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        // Hiển thị tên tác giả (có link sang trang chi tiết)
        echo "<a href='Author.php?AuthorID={$row['AuthorID']}'><b>{$row['AuthorName']}</b></a><br>";
        echo "<hr></div>";
    }
}

include 'Includes/Footer.php';
?>
