<?php
ini_set('display_errors', 3);
ini_set('display_startup_errors', 3);
error_reporting(E_ALL);
require_once '../includes/db.php';
$conn = db();

header('Content-Type: application/json');

$customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;

if ($customer_id <= 0) {
    echo json_encode([]);
    exit;
}

$query = "SELECT * FROM addresses WHERE customer_id = $customer_id AND deleted_at IS NULL";
$result = mysqli_query($conn, $query);

$addresses = [];
while ($row = mysqli_fetch_assoc($result)) {
    $addresses[] = $row;
}

echo json_encode($addresses);