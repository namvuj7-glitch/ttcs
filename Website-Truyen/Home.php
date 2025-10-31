<?php include 'Includes/Connects.php'; ?>
<?php include 'Includes/Header.php'; ?>
<link rel="stylesheet" href="Assets/Css/Home.css">
<form method="get" action="">
    Tìm truyện: 
    <input type="text" name="keyword" placeholder="Nhập tên truyện, tác giả">
    <input type="submit" value="Tìm">
</form>
<?php
if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];

    // Truy vấn tìm kiếm theo tên truyện
    $sql = "SELECT story.*, author.AuthorName
    FROM story 
    LEFT JOIN author
    ON story.AuthorID=author.AuthorID
    WHERE story.StoryName LIKE ? 
   OR (author.AuthorName IS NOT NULL AND author.AuthorName LIKE ?)";
    $stmt = $conn->prepare($sql);
    $search = "%$keyword%"; // tìm tên chứa từ khóa
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    // Hiển thị kết quả
    if($result->num_rows > 0){
      echo "<div class='story-list'>";
        while($row = $result->fetch_assoc()){
            echo "<div class='story-item'>";           
            echo "<img src='Assets/Img/{$row['Img']}' width='100'><br>";
            echo "<h3>". "<a href='Story.php?StoryID={$row['StoryID']}'><b>{$row['StoryName']}</b> </a>". "</h3>";
            echo "</div><hr>";
        }
      echo "</div>";
    } else {
        echo "Không tìm thấy truyện nào!";
    }
}
?>


<h2>Danh sách truyện mới nhất</h2>

<?php
$sql = "SELECT story.StoryName,story.Descrition,story.Img,story.PublishedDate,
story.view,story.Favourite,story.Follow,author.AuthorName,category.CategoryName,
story.StoryID
FROM Story
LEFT JOIN author
ON story.AuthorID=author.AuthorID
LEFT JOIN category
ON story.CategoryID=category.CategoryID        
                            ";
$result = $conn->query($sql);
echo "<div class='story-list'>";
while ($row = $result->fetch_assoc()) {
  echo "<div class='story-item'>";
  echo "<img src='Assets/Img/{$row['Img']}' width='100'><br>";
  echo "<a href='Story.php?StoryID={$row['StoryID']}'><b>{$row['StoryName']}</b></a><br>";
  echo "</div><hr>";
}
echo "</div>";
?>

<?php include 'Includes/footer.php'; ?>
