<?php
include("connect.php");include __DIR__ . '/../Includes/Connects.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];

$sql = "DELETE FROM chapter WHERE ChapterID='$id'";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true, "message" => "Xóa thành công"]);
} else {
    echo json_encode(["success" => false, "message" => mysqli_error($conn)]);
}
?>
