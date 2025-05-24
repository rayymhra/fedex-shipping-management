<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

startSession();
requireLogin();

$conn = db();
$current_user_id = $_SESSION['user_id'];
$shipment_id = (int) $_GET['id'];

// Validate that the shipment belongs to this courier
$query = "
    SELECT s.*
    FROM shipments s
    JOIN couriers cr ON s.courier_id = cr.id
    WHERE s.id = $shipment_id AND cr.user_id = $current_user_id
";
$result = mysqli_query($conn, $query);
$shipment = mysqli_fetch_assoc($result);

if (!$shipment) {
    echo "<div class='alert alert-danger'>Shipment not found or not assigned to you.</div>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];
    $stmt = mysqli_prepare($conn, "UPDATE shipments SET status = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $new_status, $shipment_id);
    mysqli_stmt_execute($stmt);

    header("Location: my_shipments.php");
    exit;
}

include "../includes/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Shipment Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card">
        <div class="card-header fedex-purple">
            Update Status for Shipment: <?= htmlspecialchars($shipment['tracking_code']) ?>
        </div>
        <div class="card-body">
            <form method="post">
        <div class="mb-3">
            <label class="form-label">New Status</label>
            <select name="status" class="form-select" required>
                <option value="">-- Select Status --</option>
                <?php foreach (['picked_up', 'in_transit', 'delivered'] as $status): ?>
                    <option value="<?= $status ?>" <?= $shipment['status'] === $status ? 'selected' : '' ?>>
                        <?= ucfirst(str_replace('_', ' ', $status)) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <button type="submit" class="btn btn-fedex">Update Status</button>
        <a href="my_shipments.php" class="btn btn-orange">Back</a>
    </form>
        </div>
    </div>

    
</div>
</body>
</html>
