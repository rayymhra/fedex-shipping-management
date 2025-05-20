<?php 
require_once '../includes/db.php';
require_once '../includes/functions.php';

startSession();
requireLogin();
$conn = db();

$query = "SELECT 
            s.*, 
            c.name AS customer_name, 
            u.name AS courier_name 
          FROM shipments s
          JOIN customers c ON s.customer_id = c.id
          LEFT JOIN couriers cr ON s.courier_id = cr.id
          LEFT JOIN users u ON cr.user_id = u.id
          WHERE s.deleted_at IS NULL
          ORDER BY s.created_at DESC";

$result = mysqli_query($conn, $query);

include "../includes/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shipment List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>All Shipments</h2>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Shipment created successfully!</div>
        <?php endif; ?>

        <a href="shipment_create.php" class="btn btn-primary">Add New Shipment</a>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Tracking Code</th>
                    <th>Customer</th>
                    <th>Courier</th>
                    <th>Weight (kg)</th>
                    <th>Price (IDR)</th>
                    <th>Status</th>
                    <th>Expected Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['tracking_code']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['courier_name'] ?? '-') ?></td>
                        <td><?= $row['weight_kg'] ?></td>
                        <td><?= number_format($row['price_idr'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?= match($row['status']) {
                                'pending'     => 'secondary',
                                'picked_up'   => 'info',
                                'in_transit'  => 'primary',
                                'delivered'   => 'success',
                                'cancelled'   => 'danger',
                                default       => 'dark'
                            } ?>"><?= ucfirst($row['status']) ?></span>
                        </td>
                        <td><?= $row['expected_delivery_date'] ?? '-' ?></td>
                        <td>
                            <a href="shipment_view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                    </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
</body>
</html>