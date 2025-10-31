<link rel="stylesheet" href="Assets/Css/Chapter.css">
<?php
include 'Includes/Connects.php';

// Kiểm tra có ID chương không
if (isset($_GET['ChapterID'])) {
    $chapterID = intval($_GET['ChapterID']);

    // Lấy nội dung chương
    $sql = "SELECT ChapterID, ChapterNumber, Content, StoryID 
            FROM chapter 
            WHERE ChapterID = $chapterID";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $storyID = $row['StoryID'];

        echo "<h2>Chương {$row['ChapterNumber']}</h2>";
        echo "<div style='white-space: pre-line; font-size: 18px; line-height: 1.6;'>";
        echo htmlspecialchars($row['Content']);
        echo "</div><hr>";

        // --- Lấy chương trước ---
        $prevSql = "SELECT ChapterID FROM chapter 
                    WHERE StoryID = $storyID AND ChapterID < $chapterID 
                    ORDER BY ChapterID DESC LIMIT 1";
        $prevResult = $conn->query($prevSql);
        $prevID = ($prevResult && $prevResult->num_rows > 0)
            ? $prevResult->fetch_assoc()['ChapterID']
            : null;

        // --- Lấy chương sau ---
        $nextSql = "SELECT ChapterID FROM chapter 
                    WHERE StoryID = $storyID AND ChapterID > $chapterID 
                    ORDER BY ChapterID ASC LIMIT 1";
        $nextResult = $conn->query($nextSql);
        $nextID = ($nextResult && $nextResult->num_rows > 0)
            ? $nextResult->fetch_assoc()['ChapterID']
            : null;

        // --- Nút điều hướng ---
        echo "<div style='margin-top:20px;'>";
        if ($prevID) {
            echo "<a href='Chapter.php?id=$prevID'>← Chương trước</a> | ";
        }
        echo "<a href='Story.php?id=$storyID'>Về truyện</a>";
        if ($nextID) {
            echo " | <a href='Chapter.php?id=$nextID'>Chương sau →</a>";
        }
        echo "</div>";

    } else {
        echo "Không tìm thấy chương.";
    }
} else {
    echo "Thiếu ID chương.";
}
?>
