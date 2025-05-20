<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();

// requireAdmin();
$conn = db();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $notes = $_POST['notes'];

    $query = "INSERT INTO customers (name, email, phone, notes, created_at, updated_at) VALUES ('$name', '$email', '$phone', '$notes', NOW(), NOW())";

    if (mysqli_query($conn, $query)) {
        header("Location: customer_management.php?success=Customer added successfully");
        exit;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-4">
    <h2>Add New Customer</h2>

    <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Add Customer</button>
        <a href="customer_list_staff.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>