<?php
session_start();
?>
<link rel="stylesheet" href="Assets/Css/Header.css">
<header class="no-bootstrap-header">
  <h1><a href="Home.php">🕮 TruyệnKMA</a></h1>
  <nav>
    <a href="Home.php">Trang chủ</a> |
    <a href="CategoryList.php">Thể loại</a> |
    <a href="AuthorList.php">Tác giả<br></a> |

    <?php if(isset($_SESSION['UserID'])): ?>
        <!-- Nếu đã đăng nhập -->
        <span class="user-menu">
            Xin chào, <?php echo $_SESSION['UserName']; ?>
            <div class="dropdown">
                <a href="Logout.php">Đăng xuất</a>
                <a href="FollowList.php">Truyện theo dõi</a>
            </div>
        </span>
    <?php else: ?>
        <!-- Nếu chưa đăng nhập -->
        <a href="Register.php">Đăng ký</a> |
        <a href="Login.php">Đăng nhập</a>
    <?php endif; ?>
  </nav>
  <hr>
</header>
