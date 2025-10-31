<?php
header("Content-Type: application/json; charset=UTF-8");
include '../Includes/Connects.php';

// Lấy tất cả thể loại
$sql = "SELECT CategoryID, CategoryName FROM category ORDER BY CategoryName ASC";
$result = $conn->query($sql);

$categories = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = [
            "CategoryID" => $row["CategoryID"],
            "CategoryName" => $row["CategoryName"]
        ];
    }
}

// Trả về JSON
echo json_encode($categories, JSON_UNESCAPED_UNICODE);
?>