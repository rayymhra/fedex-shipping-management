<?php
require_once '../includes/db.php';
$conn = db();

$shipment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($shipment_id <= 0) {
    echo "Invalid shipment ID.";
    exit;
}

$sql = "DELETE FROM shipments WHERE id = $shipment_id";
if (mysqli_query($conn, $sql)) {
    header("Location: shipments.php?deleted=1");
    exit;
} else {
    echo "Error deleting shipment: " . mysqli_error($conn);
}