<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();

// requireAdmin();
$conn = db();
$customer_id = $_GET['id'];

$customer_query = "SELECT * FROM customers WHERE id = $customer_id AND deleted_at IS NULL";
$customer_result = mysqli_query($conn, $customer_query);
$customer = mysqli_fetch_assoc($customer_result);

// Fetch addresses for this customer
$query = "SELECT * FROM addresses WHERE customer_id = $customer_id AND deleted_at IS NULL ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
include "../includes/header.php"
?>

<h2>Customer Detail</h2>
    <a href="customer_management.php" class="btn btn-secondary mb-4">← Back to Customer List</a>

    <div class="card mb-4">
        <div class="card-header">
            <strong><?= htmlspecialchars($customer['name']) ?></strong>
        </div>
        <div class="card-body">
            <p><strong>Email:</strong> <?= htmlspecialchars($customer['email'] ?? '-') ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($customer['phone'] ?? '-') ?></p>
            <p><strong>Notes:</strong> <?= nl2br(htmlspecialchars($customer['notes'] ?? '-')) ?></p>
            <p><strong>Created At:</strong> <?= $customer['created_at'] ?></p>
            <p><strong>Updated At:</strong> <?= $customer['updated_at'] ?></p>
            <a href="customer_edit.php?id=<?= $customer_id ?>" class="btn btn-warning">Edit Customer</a>
        </div>
    </div>

<h3>Addresses</h3>
<a href="address_add.php?customer_id=<?= $customer_id ?>" class="btn btn-primary mb-3">Add Address</a>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>Label</th>
      <th>Street</th>
      <th>City</th>
      <th>Province</th>
      <th>Postal Code</th>
      <th>Country</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($address = mysqli_fetch_assoc($result)) : ?>
      <tr>
        <td><?= htmlspecialchars($address['label']) ?></td>
        <td><?= htmlspecialchars($address['street']) ?></td>
        <td><?= htmlspecialchars($address['city']) ?></td>
        <td><?= htmlspecialchars($address['province']) ?></td>
        <td><?= htmlspecialchars($address['postal_code']) ?></td>
        <td><?= htmlspecialchars($address['country']) ?></td>
        <td>
          <a href="address_edit.php?id=<?= $address['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
          <a href="address_delete.php?id=<?= $address['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this address?')">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>