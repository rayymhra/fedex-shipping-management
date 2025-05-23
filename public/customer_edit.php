<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();

// requireAdmin();
$conn = db();
$id = $_GET['id'];
$query = "SELECT * FROM customers WHERE id = $id";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

if (!$customer) {
    die("Customer not found.");
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $notes = $_POST['notes'];

    $update = "UPDATE customers SET name='$name', email='$email', phone='$phone', notes='$notes', updated_at=NOW() WHERE id=$id";

    if (mysqli_query($conn, $update)) {
        header("Location: customer_management.php?success=Customer updated successfully");
        exit;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header fedex-purple">
            Edit Customer
        </div>
        <div class="card-body">
            <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($customer['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control"><?= htmlspecialchars($customer['notes']) ?></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-fedex">Update Customer</button>
        <a href="customer_list_staff.php" class="btn btn-orange">Cancel</a>
    </form>
</div>
        </div>
    </div>

    

<?php include '../includes/footer.php'; ?>