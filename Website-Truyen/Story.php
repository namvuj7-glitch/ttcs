<?php
include 'Includes/Header.php';
?>
<!-- Thêm Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="Assets/Css/Story.css">
<?php
include 'Includes/Connects.php';
?>
<?php
// Lấy ID truyện từ URL
$storyID = $_GET['StoryID'] ?? 0;

// Cập nhật lượt xem
$conn->query("UPDATE story SET view = view + 1 WHERE StoryID = $storyID");

// Lấy thông tin truyện
$sql = "SELECT story.*, author.AuthorName, category.CategoryName 
        FROM story
        LEFT JOIN author ON story.AuthorID = author.AuthorID
        LEFT JOIN category ON story.CategoryID = category.CategoryID
        WHERE story.StoryID = $storyID";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$row) {
    echo "<div class='alert alert-danger text-center mt-5'>Không tìm thấy truyện này.</div>";
    include 'Includes/Footer.php';
    exit;
}
?>

<div class="container mt-4">
    <div class="row">
        <!-- Ảnh bìa -->
        <div class="col-md-3 text-center">
            <?php if (!empty($row['Img'])): ?>
                <img src="Assets/Img/<?= $row['Img'] ?>" class="img-fluid rounded shadow" alt="Bìa truyện">
            <?php endif; ?>
        </div>

        <!-- Thông tin truyện -->
        <div class="col-md-9">
            <h2 class="fw-bold"><?= $row['StoryName'] ?></h2>
            <p class="text-muted"><?= nl2br($row['Descrition']) ?></p>

            <p><b>Tác giả:</b> <a href="Author.php?AuthorID=<?= $row['AuthorID'] ?>"><?= $row['AuthorName'] ?></a></p>
            <p><b>Thể loại:</b> <a href="Category.php?CategoryID=<?= $row['CategoryID'] ?>"><?= $row['CategoryName'] ?></a></p>

            <div class="d-flex gap-3">
                <span><b>Lượt xem:</b> <?= $row['view'] ?></span>
                <span><b>Thích:</b> <?= $row['Favourite'] ?></span>
                <span><b>Theo dõi:</b> <?= $row['Follow'] ?></span>
            </div>
        </div>
    </div>

    <hr>

    <!-- Danh sách chương -->
    <h4 class="mt-4">Danh sách chương</h4>
    <?php
    $chapterSql = "SELECT * FROM chapter WHERE StoryID = $storyID ORDER BY ChapterNumber ASC";
    $chapters = $conn->query($chapterSql);

    if ($chapters->num_rows > 0) {
        echo "<ul id='chapterList' class='list-group'>";
        $index = 0;
        while ($ch = $chapters->fetch_assoc()) {
            $index++;
            $style = ($index > 5) ? "style='display:none'" : "";
            echo "<li class='list-group-item' $style>
                    <a href='Chapter.php?ChapterID={$ch['ChapterID']}'>
                        Chương {$ch['ChapterNumber']}
                    </a>
                    <small class='text-muted'>({$ch['PublishedDate']})</small>
                  </li>";
        }
        echo "</ul>";

        if ($chapters->num_rows > 5) {
            echo "<div class='text-center mt-3'>
                    <button id='toggleButton' class='btn btn-outline-primary' onclick='toggleChapters()'>Xem thêm</button>
                  </div>";
        }
    } else {
        echo "<p>Truyện này chưa có chương nào.</p>";
    }
    ?>

    <!-- Like / Follow -->
    <div class="mt-4">
    <?php
    if (isset($_SESSION['UserID'])) {
        $userID = $_SESSION['UserID'];

        // Kiểm tra theo dõi
        $check = $conn->query("SELECT * FROM follow WHERE StoryID=$storyID AND UserID=$userID");
        if ($check->num_rows > 0) {
            echo "
            <form method='post'>
                <button type='submit' name='unfollow' class='btn btn-danger'>Bỏ theo dõi</button>
            </form>";
        } else {
            echo "
            <form method='post'>
                <button type='submit' name='follow' class='btn btn-success'>Theo dõi truyện này</button>
            </form>";
        }

        // Xử lý theo dõi
        if (isset($_POST['follow'])) {
            $conn->query("INSERT INTO follow (StoryID, UserID) VALUES ($storyID, $userID)");
            $conn->query("UPDATE story SET Follow = Follow + 1 WHERE StoryID = $storyID");
            header("Location: Story.php?StoryID=$storyID");
            exit;
        }

        if (isset($_POST['unfollow'])) {
            $conn->query("DELETE FROM follow WHERE StoryID = $storyID AND UserID = $userID");
            $conn->query("UPDATE story SET Follow = GREATEST(Follow - 1, 0) WHERE StoryID = $storyID");
            header("Location: Story.php?StoryID=$storyID");
            exit;
        }

        // Like
        echo "
        <form method='post' class='mt-2'>
            <button type='submit' name='like' class='btn btn-outline-danger'>❤️ Thích</button>
        </form>";

        if (isset($_POST['like'])) {
            $conn->query("UPDATE story SET Favourite = Favourite + 1 WHERE StoryID = $storyID");
            header("Location: Story.php?StoryID=$storyID");
            exit;
        }
    } else {
        echo "<p><a href='Login.php'>Đăng nhập</a> để thích hoặc theo dõi truyện.</p>";
    }
    ?>
    </div>

    <!-- Bình luận -->
    <hr>
    <h4>Bình luận</h4>
    <?php
    $sql = "SELECT comment.Contentc, comment.CreatAt, user.UserName 
            FROM comment 
            JOIN user ON comment.UserID = user.UserID
            WHERE comment.StoryID = $storyID 
            ORDER BY comment.CreatAt DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='list-group'>";
        while ($cmt = $result->fetch_assoc()) {
            echo "
            <div class='list-group-item'>
                <b>{$cmt['UserName']}</b> 
                <small class='text-muted'>({$cmt['CreatAt']})</small>
                <p class='mb-0'>" . nl2br(htmlspecialchars($cmt['Contentc'])) . "</p>
            </div>";
        }
        echo "</div>";
    } else {
        echo "<p>Chưa có bình luận nào.</p>";
    }

    if (isset($_SESSION['UserID'])) {
        echo "
        <form method='post' class='mt-3'>
            <textarea name='content' rows='3' class='form-control mb-2' placeholder='Nhập bình luận...'></textarea>
            <button type='submit' name='submit_comment' class='btn btn-primary'>Gửi bình luận</button>
        </form>
        ";
    }

    if (isset($_POST['submit_comment'])) {
        $content = trim($_POST['content']);
        if ($content != '') {
            $userID = $_SESSION['UserID'];
            $conn->query("INSERT INTO comment (StoryID, UserID, Contentc) VALUES ($storyID, $userID, '$content')");
            header("Location: Story.php?StoryID=$storyID");
            exit;
        }
    }
    ?>
</div>

<script>
function toggleChapters() {
    const list = document.querySelectorAll('#chapterList li');
    const btn = document.getElementById('toggleButton');
    let hidden = false;
    for (let i = 5; i < list.length; i++) {
        if (list[i].style.display === 'none') hidden = true;
    }
    if (hidden) {
        for (let i = 5; i < list.length; i++) list[i].style.display = 'list-item';
        btn.textContent = 'Thu gọn';
    } else {
        for (let i = 5; i < list.length; i++) list[i].style.display = 'none';
        btn.textContent = 'Xem thêm';
    }
}
</script>

<?php include 'Includes/Footer.php'; ?>
