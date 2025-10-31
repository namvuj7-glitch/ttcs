<link rel="stylesheet" href="Assets/Css/Login.css">
<?php
session_start();
include 'Includes/Connects.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['UserName']);
    $password = trim($_POST['Password']);

    if ($username == '' || $password == '') {
        echo "Vui lòng nhập đầy đủ thông tin!";
    } else {
        // Lấy thông tin user theo username
        $sql = "SELECT * FROM user WHERE UserName='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Kiểm tra mật khẩu
            if ($password===$user['Password']) {
                // Lưu session
                $_SESSION['UserID'] = $user['UserID'];
                $_SESSION['UserName'] = $user['UserName'];
                $_SESSION['Role'] = $user['Role'];

                echo "Đăng nhập thành công!<br>";

                // Chuyển hướng theo role
                if ($user['Role'] == 0) {
            header("Location: Admin/Admin.php");
            exit();
           } else{
            header("Location: Home.php");
            exit();}
           }
           else  {
                echo "Sai mật khẩu!";
            }
        } else {
            echo "Tài khoản không tồn tại!";
        }
    }
}
?>
<div class="login-container">
<h2>Đăng nhập</h2>
<form method="post" action="">
    Tên đăng nhập: <input type="text" name="UserName"><br><br>
    Mật khẩu: <input type="password" name="Password"><br><br>
    <input type="submit" value="Đăng nhập">
</form>
</div>