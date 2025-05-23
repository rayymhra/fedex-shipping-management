<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();

// requireAdmin();
$conn = db();
$query = "SELECT * FROM customers WHERE deleted_at IS NULL ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<?php include '../includes/header.php'; ?>


<div class="container mt-4">
    <h3 class="mb-4 text-center">Manage Customers</h3>

    <a href="customer_add.php" class="btn btn-fedex mb-3">Add Customer</a>

    <div class="card">
        <div class="card-header fedex-purple">
            Customers List
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
        <thead class="table-warning">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Notes</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($customer = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($customer['name']) ?></td>
                <td><?= htmlspecialchars($customer['email']) ?></td>
                <td><?= htmlspecialchars($customer['phone']) ?></td>
                <td><?= htmlspecialchars($customer['notes']) ?></td>
                <td><?= date("Y-m-d", strtotime($customer['created_at'])) ?></td>
                <td>
                    <a href="customer_view.php?id=<?= $customer['id'] ?>" class="btn btn-fedex btn-sm">View</a>
                    <!-- <a href="customer_edit.php?id=<?= $customer['id'] ?>" class="btn btn-warning btn-sm">Edit</a> -->
                    <a href="customer_delete.php?id=<?= $customer['id'] ?>" class="btn btn-orange btn-sm" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
        </div>
    </div>
    
</div>

<?php include '../includes/footer.php'; ?>