<?php
include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost:5173");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$data = json_decode(file_get_contents("php://input"), true);
$userID = $data["id"] ?? 0;

if ($userID == 0) {
    echo json_encode(["success" => false, "message" => "Thiếu UserID"]);
    exit;
}

$sql = "DELETE FROM user WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Xóa thất bại"]);
}
?>
