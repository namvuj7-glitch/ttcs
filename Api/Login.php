<?php
session_start();
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? '';
$password = $data['password'] ?? '';

$sql = "SELECT * FROM user WHERE UserName='$name' AND Password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['UserID'] = $user['UserID'];
    echo json_encode(["success" => true, "user" => $user]);
} else {
    echo json_encode(["success" => false]);
}
