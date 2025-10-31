<?php
session_start();
include __DIR__ . '/../Includes/Connects.php';

// Kiểm tra đăng nhập và role
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] != 0) {
    echo "Bạn không có quyền truy cập trang admin!";
    echo "<br><a href='Home.php'>Về trang chủ</a>";
    exit;
}
?>

<h2>Trang Quản Trị</h2>
<p>Chào <b><?php echo $_SESSION['UserName']; ?></b></p>

<ul>
    <li><a href="AStory.php">Quản lý truyện (AStory)</a></li>
    <li><a href="AUser.php">Quản lý người dùng (AUser)</a></li>
</ul>

<p><a href="Logout.php">Đăng xuất</a></p>
