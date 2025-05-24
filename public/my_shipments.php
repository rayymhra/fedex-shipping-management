<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

startSession();
requireLogin();

$conn = db();

// Get current user's ID
$current_user_id = $_SESSION['user_id'];

// Fetch shipments assigned to this courier
$query = "
    SELECT 
        s.*, 
        c.name AS customer_name
    FROM shipments s
    JOIN customers c ON s.customer_id = c.id
    JOIN couriers cr ON s.courier_id = cr.id
    WHERE cr.user_id = $current_user_id
    AND s.deleted_at IS NULL
    ORDER BY s.created_at DESC
";

$result = mysqli_query($conn, $query);

include "../includes/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Shipments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card">
        <div class="card-header fedex-purple">
            My assigned shipments
        </div>
        <div class="card-body">
            <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-warning">
                <tr>
                    <th>Tracking Code</th>
                    <th>Customer</th>
                    <th>Weight (kg)</th>
                    <th>Status</th>
                    <th>Expected Delivery</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['tracking_code']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= $row['weight_kg'] ?></td>
                        <td>
                            <span class="badge bg-<?= match($row['status']) {
                                'pending'     => 'secondary',
                                'picked_up'   => 'info',
                                'in_transit'  => 'primary',
                                'delivered'   => 'success',
                                'cancelled'   => 'danger',
                                default       => 'dark'
                            } ?>">
                                <?= ucfirst(str_replace('_', ' ', $row['status'])) ?>
                            </span>
                        </td>
                        <td><?= $row['expected_delivery_date'] ?? '-' ?></td>
                        <td>
                            <a href="update_status.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-fedex">
                                Update Status
                            </a>
                        </td>

                        <!-- <td>
                            <a href="shipment_tracking.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">Track</a>
                        </td> -->
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No shipments assigned to you yet.</div>
    <?php endif ?>
        </div>
    </div>

    
</div>
</body>
</html>