<?php
session_start();

// Xóa toàn bộ session
session_unset();   // Xóa các biến session
session_destroy(); // Hủy session hiện tại

// Quay lại trang chủ (index.php)
header("Location: ../Home.php");
exit;
?>
