<?php
session_start();
include __DIR__ . '/../Includes/Connects.php';

// Chặn người không phải admin
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] != 0) {
    echo "Bạn không có quyền truy cập trang admin!";
    echo "<br><a href='Home.php'>Về trang chủ</a>";
    exit;
}

// --- Xóa truyện ---
if (isset($_GET['delete'])) {
    $storyID = intval($_GET['delete']);
    $conn->query("DELETE FROM story WHERE StoryID = $storyID");
    // Xóa luôn chương liên quan
    $conn->query("DELETE FROM chapter WHERE StoryID = $storyID");
}

// --- Thêm truyện mới ---
if(isset($_POST['add_story'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $img = $_POST['img'];
    $authorName = $_POST['author'];
    $categoryName = $_POST['category'];

    // 1. Kiểm tra nếu tác giả đã tồn tại trong DB, nếu chưa thì thêm
    $authorCheck = $conn->query("SELECT AuthorID FROM author WHERE AuthorName = '$authorName'");
    if($authorCheck->num_rows > 0){
        $authorID = $authorCheck->fetch_assoc()['AuthorID'];
    } else {
        $conn->query("INSERT INTO author (AuthorName) VALUES ('$authorName')");
        $authorID = $conn->insert_id;
    }

    // 2. Kiểm tra nếu thể loại đã tồn tại trong DB, nếu chưa thì thêm
    $categoryCheck = $conn->query("SELECT CategoryID FROM category WHERE CategoryName = '$categoryName'");
    if($categoryCheck->num_rows > 0){
        $categoryID = $categoryCheck->fetch_assoc()['CategoryID'];
    } else {
        $conn->query("INSERT INTO category (CategoryName) VALUES ('$categoryName')");
        $categoryID = $conn->insert_id;
    }

    // 3. Thêm truyện mới
    $conn->query("INSERT INTO story (Title, Description, Img, AuthorID, CategoryID) 
                 VALUES ('$title', '$description', '$img', $authorID, $categoryID)");

    echo "Thêm truyện thành công!";
}


// --- Thêm chương mới ---
if (isset($_POST['add_chapter'])) {
    $storyID = intval($_POST['story_id']);
    $content = trim($_POST['content']);
    if ($content != '') {
        $sql = "INSERT INTO chapter (StoryID, Content) VALUES ($storyID, '$content')";
        $conn->query($sql);
    }
}

// --- Lấy danh sách truyện ---
$sql = "SELECT story.StoryID, story.StoryName, author.AuthorName, category.CategoryName 
        FROM story 
        LEFT JOIN author ON story.AuthorID = author.AuthorID 
        LEFT JOIN category ON story.CategoryID = category.CategoryID
        ORDER BY story.StoryID ASC";
$result = $conn->query($sql);

// --- Lấy danh sách tác giả và thể loại để form ---
$authors = $conn->query("SELECT * FROM author");
$categories = $conn->query("SELECT * FROM category");
?>

<h2>Quản lý truyện</h2>
<p><a href="Admin.php">Về trang Admin</a></p>

<h3>Danh sách truyện</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Tiêu đề</th>
        <th>Tác giả</th>
        <th>Thể loại</th>
        <th>Hành động</th>
    </tr>
    <?php while ($story = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $story['StoryID']; ?></td>
        <td><?php echo $story['StoryName']; ?></td>
        <td><?php echo $story['AuthorName']; ?></td>
        <td><?php echo $story['CategoryName']; ?></td>
        <td>
            <a href="AStory.php?delete=<?php echo $story['StoryID']; ?>" onclick="return confirm('Xóa truyện này?');">Xóa</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<h3>Thêm truyện mới</h3>
<form method="post" action="">
    Tiêu đề: <input type="text" name="title"><br><br>

    Mô tả: <textarea name="description" rows="3" cols="50"></textarea><br><br>

    Ảnh bìa (tên file trong Assets/Img): <input type="text" name="img"><br><br>

    Tác giả: <input type="text" name="author" placeholder="Nhập tên tác giả"><br><br>

    Thể loại: <input type="text" name="category" placeholder="Nhập thể loại"><br><br>

    <input type="submit" name="add_story" value="Thêm truyện">
</form>


<h3>Thêm chương cho truyện</h3>
<form method="post" action="">
    Chọn truyện:
    <select name="story_id">
        <?php
        $stories = $conn->query("SELECT StoryID, StoryName FROM story ORDER BY StoryID ASC");
        while($s = $stories->fetch_assoc()): ?>
            <option value="<?php echo $s['StoryID']; ?>"><?php echo $s['StoryName']; ?></option>
        <?php endwhile; ?>
    </select><br><br>
    Nội dung chương: <br>
    <textarea name="content" rows="5" cols="60"></textarea><br><br>
    <input type="submit" name="add_chapter" value="Thêm chương">
</form>
