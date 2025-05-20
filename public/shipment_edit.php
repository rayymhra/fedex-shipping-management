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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_label = mysqli_real_escape_string($conn, $_POST['recipient_label']);
    $recipient_street = mysqli_real_escape_string($conn, $_POST['recipient_street']);
    $recipient_city = mysqli_real_escape_string($conn, $_POST['recipient_city']);
    $recipient_province = mysqli_real_escape_string($conn, $_POST['recipient_province']);
    $recipient_postal_code = mysqli_real_escape_string($conn, $_POST['recipient_postal_code']);
    $recipient_country = mysqli_real_escape_string($conn, $_POST['recipient_country']);
    $weight_kg = floatval($_POST['weight_kg']);
    $dimensions_cm = mysqli_real_escape_string($conn, $_POST['dimensions_cm']);
    $price_idr = floatval($_POST['price_idr']);
    $expected_delivery_date = mysqli_real_escape_string($conn, $_POST['expected_delivery_date']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $update = "UPDATE shipments SET 
        recipient_label='$recipient_label',
        recipient_street='$recipient_street',
        recipient_city='$recipient_city',
        recipient_province='$recipient_province',
        recipient_postal_code='$recipient_postal_code',
        recipient_country='$recipient_country',
        weight_kg=$weight_kg,
        dimensions_cm='$dimensions_cm',
        price_idr=$price_idr,
        expected_delivery_date='$expected_delivery_date',
        notes='$notes'
        WHERE id=$shipment_id";

    if (mysqli_query($conn, $update)) {
        header("Location: shipment_view.php?id=$shipment_id&updated=1");
        exit;
    } else {
        echo "Error updating: " . mysqli_error($conn);
    }
}

// Get current shipment data
$result = mysqli_query($conn, "SELECT * FROM shipments WHERE id=$shipment_id");
$shipment = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Shipment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2>Edit Shipment</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Recipient Label</label>
            <input type="text" name="recipient_label" class="form-control" value="<?= htmlspecialchars($shipment['recipient_label']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Street</label>
            <input type="text" name="recipient_street" class="form-control" value="<?= htmlspecialchars($shipment['recipient_street']) ?>" required>
        </div>
        <div class="mb-3">
            <label>City</label>
            <input type="text" name="recipient_city" class="form-control" value="<?= htmlspecialchars($shipment['recipient_city']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Province</label>
            <input type="text" name="recipient_province" class="form-control" value="<?= htmlspecialchars($shipment['recipient_province']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Postal Code</label>
            <input type="text" name="recipient_postal_code" class="form-control" value="<?= htmlspecialchars($shipment['recipient_postal_code']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Country</label>
            <input type="text" name="recipient_country" class="form-control" value="<?= htmlspecialchars($shipment['recipient_country']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Weight (kg)</label>
            <input type="number" step="0.01" name="weight_kg" class="form-control" value="<?= $shipment['weight_kg'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Dimensions (cm)</label>
            <input type="text" name="dimensions_cm" class="form-control" value="<?= htmlspecialchars($shipment['dimensions_cm']) ?>">
        </div>
        <div class="mb-3">
            <label>Price (IDR)</label>
            <input type="number" name="price_idr" class="form-control" value="<?= $shipment['price_idr'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Expected Delivery Date</label>
            <input type="date" name="expected_delivery_date" class="form-control" value="<?= $shipment['expected_delivery_date'] ?>">
        </div>
        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($shipment['notes']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">💾 Save Changes</button>
        <a href="shipment_view.php?id=<?= $shipment_id ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>