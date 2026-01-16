<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

$check = $conn->query("SELECT * FROM user WHERE UserName='$name'");
if ($check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Tên đăng nhập đã tồn tại"]);
    exit;
}

$sql = "INSERT INTO user (UserName, Email, Password) VALUES ('$name', '$email', '$password')";
if ($conn->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Lỗi server"]);
}
