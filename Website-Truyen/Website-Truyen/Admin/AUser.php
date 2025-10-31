<?php
session_start();
include __DIR__ . '/../Includes/Connects.php';

// Chặn người không phải admin
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] != 0) {
    echo "Bạn không có quyền truy cập trang admin!";
    echo "<br><a href='Home.php'>Về trang chủ</a>";
    exit;
}

// Xử lý xóa user
if (isset($_GET['delete'])) {
    $delID = intval($_GET['delete']);

    // Không cho xóa admin hiện tại
    if ($delID != $_SESSION['UserID']) {
        $sqlDel = "DELETE FROM user WHERE id = $delID";
        $conn->query($sqlDel);
    } else {
        echo "Bạn không thể xóa chính mình!";
    }
}

// Xử lý thêm user
if (isset($_POST['add_user'])) {
    $username = trim($_POST['UserName']);
    $password = trim($_POST['Password']);
    $email = trim($_POST['Email']);
    $role = intval($_POST['Role']); // 0=admin, 1=user

    if ($username != '' && $password != '') {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sqlInsert = "INSERT INTO users (UserName, Password, Email, Role) 
                      VALUES ('$username', '$hashed', '$email', $role)";
        $conn->query($sqlInsert);
    }
}

// Lấy danh sách user
$sql = "SELECT * FROM user ORDER BY UserID ASC";
$result = $conn->query($sql);
?>

<h2>Quản lý người dùng</h2>
<p><a href="Admin.php">Về trang Admin</a></p>

<h3>Danh sách người dùng</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Hành động</th>
    </tr>
    <?php while ($user = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $user['UserID']; ?></td>
        <td><?php echo $user['UserName']; ?></td>
        <td><?php echo $user['Email']; ?></td>
        <td><?php echo $user['Role'] == 0 ? "Admin" : "User"; ?></td>
        <td>
            <?php if ($user['UserID'] != $_SESSION['UserID']): ?>
                <a href="AUser.php?delete=<?php echo $user['UserID']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<h3>Thêm người dùng mới</h3>
<form method="post" action="">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>
    Email: <input type="email" name="email"><br><br>
    Role: 
    <select name="role">
        <option value="1">User</option>
        <option value="0">Admin</option>
    </select><br><br>
    <input type="submit" name="add_user" value="Thêm người dùng">
</form>
