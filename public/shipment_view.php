<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

startSession();
requireLogin();
$conn = db();

$shipment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($shipment_id <= 0) {
    echo "Invalid shipment ID.";
    exit;
}

// Query the shipment data
$sql = "SELECT * FROM shipments WHERE id = $shipment_id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Shipment not found.";
    exit;
}

$shipment = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shipment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">Shipment Details</h2>
    <a href="shipments.php" class="btn btn-secondary mb-4">← Back to List</a>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Tracking Code: <?= htmlspecialchars($shipment['tracking_code']) ?></h5>
            <p><strong>Customer ID:</strong> <?= $shipment['customer_id'] ?></p>
            <p><strong>Sender Address ID:</strong> <?= $shipment['sender_address_id'] ?></p>
            <hr>
            <p><strong>Recipient:</strong> <?= htmlspecialchars($shipment['recipient_label']) ?></p>
            <p><strong>Street:</strong> <?= htmlspecialchars($shipment['recipient_street']) ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($shipment['recipient_city']) ?></p>
            <p><strong>Province:</strong> <?= htmlspecialchars($shipment['recipient_province']) ?></p>
            <p><strong>Postal Code:</strong> <?= htmlspecialchars($shipment['recipient_postal_code']) ?></p>
            <p><strong>Country:</strong> <?= htmlspecialchars($shipment['recipient_country']) ?></p>
            <hr>
            <p><strong>Weight (kg):</strong> <?= $shipment['weight_kg'] ?></p>
            <p><strong>Dimensions (cm):</strong> <?= htmlspecialchars($shipment['dimensions_cm']) ?></p>
            <p><strong>Price (IDR):</strong> Rp <?= number_format($shipment['price_idr'], 0, ',', '.') ?></p>
            <p><strong>Expected Delivery:</strong> <?= $shipment['expected_delivery_date'] ?: 'N/A' ?></p>
            <p><strong>Courier ID:</strong> <?= $shipment['courier_id'] ?></p>
            <p><strong>Notes:</strong> <?= htmlspecialchars($shipment['notes']) ?></p>
            <p><strong>Created At:</strong> <?= $shipment['created_at'] ?></p>
        </div>
    </div>
</div>
</body>
</html>