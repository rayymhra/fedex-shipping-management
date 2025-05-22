<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();
$conn = db();

$customers = mysqli_query($conn, "SELECT * FROM customers WHERE deleted_at IS NULL ORDER BY created_at DESC");

?>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">Customer List</h2>
    <div class="card">
        <div class="card-header fedex-purple">
            Customer List
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
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($customer = mysqli_fetch_assoc($customers)) :
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($customer['name']) ?></td>
                <td><?= htmlspecialchars($customer['email']) ?></td>
                <td><?= htmlspecialchars($customer['phone']) ?></td>
                <td><?= htmlspecialchars($customer['notes']) ?></td>
                <td><?= date("Y-m-d", strtotime($customer['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
        </div>
    </div>
    
</div>

<?php include '../includes/footer.php'; ?>