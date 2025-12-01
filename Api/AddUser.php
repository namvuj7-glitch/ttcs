<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);
$name = $data["name"];
$password = password_hash($data["password"], PASSWORD_DEFAULT);
$email = $data["email"];
$role = $data["role"] ?? "user";

$sql = "INSERT INTO user (UserName, Password, Email, Role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $name, $password, $email, $role);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Thêm thất bại"]);
}
?>
