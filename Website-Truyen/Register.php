<link rel="stylesheet" href="Assets/Css/Register.css">
<?php
include 'Includes/Connects.php';

// Khi người dùng nhấn nút Đăng ký
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['UserName']);
    $password = trim($_POST['Password']);
    $email = trim($_POST['Email']);

    if ($username == '' || $password == '') {
        echo "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu!";
    } else {
        // Mã hóa mật khẩu
       // $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Kiểm tra trùng tên đăng nhập
        $check = "SELECT * FROM user WHERE user.UserName='$username'";
        $result = $conn->query($check);

        if ($result->num_rows > 0) {
            echo "Tên đăng nhập đã tồn tại!";
        } else {
            // role mặc định = 1 (do database đặt sẵn)
            $sql = "INSERT INTO users (username, password, email) 
                    VALUES ('$username', '$password', '$email')";
            if ($conn->query($sql)) {
                echo "Đăng ký thành công! <a href='Login.php'>Đăng nhập</a>";
            } else {
                echo "Lỗi: " . $conn->error;
            }
        }
    }
}
?>

<h2>Đăng ký tài khoản</h2>
<form method="post" action="">
    Tên đăng nhập: <input type="text" name="username"><br><br>
    Mật khẩu: <input type="password" name="password"><br><br>
    Email: <input type="email" name="email"><br><br>
    <input type="submit" value="Đăng ký">
</form>
