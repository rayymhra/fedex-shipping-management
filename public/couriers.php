<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();

// requireAdmin();
$conn = db();
$role = $_SESSION['role'];


// $result = mysqli_query($conn, "SELECT * FROM couriers WHERE deleted_at IS NULL");

$query = "SELECT users.*, couriers.id AS courier_id 
          FROM users 
          LEFT JOIN couriers ON users.id = couriers.user_id
          WHERE users.role = 'courier'
          ";
$result = mysqli_query($conn, $query);

include "../includes/header.php";
?>

<div class="container py-4">
    <h2>Courier Management</h2>
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td>
              <?= $row['courier_id'] ? '<span class="badge bg-success">Info Set</span>' : '<span class="badge bg-secondary">Not Set</span>' ?>
            </td>
            <td>
              <?php if ($row['courier_id']) { ?>
                <a href="courier_edit_info.php?id=<?= $row['courier_id'] ?>" class="btn btn-sm btn-warning">Edit Info</a>
                <a href="courier_view_info.php?id=<?= $row['courier_id'] ?>" class="btn btn-sm btn-info text-white">See Info</a>
              <?php } else { ?>
                <a href="courier_add_info.php?user_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Add Info</a>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>