<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();
$conn = db();

$role = $_SESSION['role'];
include "../includes/header.php";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <title>Dashboard</title>
</head>
<body>

<h2 class="mb-4">Dashboard – <?= ucfirst($role) ?></h2>

<?php

if ($role === 'admin'): ?>

  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <a class="btn btn-primary w-100 py-3" href="users.php">
        <strong>Manage Users</strong><br><span class="small">Admins, staff, couriers</span>
      </a>
    </div>
    <div class="col-md-3">
      <a class="btn btn-primary w-100 py-3" href="couriers.php">
        <strong>Couriers</strong><br><span class="small">Add / retire couriers</span>
      </a>
    </div>
    <div class="col-md-3">
      <a class="btn btn-primary w-100 py-3" href="customers.php">
        <strong>Customers</strong><br><span class="small">Full customer list</span>
      </a>
    </div>
    <div class="col-md-3">
      <a class="btn btn-primary w-100 py-3" href="reports.php">
        <strong>Reports</strong><br><span class="small">Analytics & KPIs</span>
      </a>
    </div>
  </div>

  <!-- Optional quick stats -->
  <?php
  $shipmentsToday = mysqli_fetch_row(
      mysqli_query($conn, "SELECT COUNT(*) FROM shipments WHERE DATE(created_at)=CURDATE()")
  )[0];
  ?>
  <div class="alert alert-info">
      📦 Shipments created today: <strong><?= $shipmentsToday ?></strong>
  </div>



  
<?php
elseif ($role === 'staff'): ?>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <a class="btn btn-secondary w-100 py-3" href="customers.php">
        <strong>Customers</strong><br><span class="small">Create / edit customers</span>
      </a>
    </div>
    <div class="col-md-4">
      <a class="btn btn-secondary w-100 py-3" href="shipments.php">
        <strong>Shipments</strong><br><span class="small">New shipment, assign courier</span>
      </a>
    </div>
    <div class="col-md-4">
      <a class="btn btn-secondary w-100 py-3" href="tracking.php">
        <strong>Add Tracking</strong><br><span class="small">Update shipment status</span>
      </a>
    </div>
  </div>

<?php
/* ===============================================================
   COURIER DASHBOARD
   ===============================================================*/
else: // courier ?>

  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <a class="btn btn-success w-100 py-3" href="my_shipments.php">
        <strong>My Shipments</strong><br><span class="small">Jobs assigned to me</span>
      </a>
    </div>
    <div class="col-md-6">
      <a class="btn btn-success w-100 py-3" href="tracking.php">
        <strong>Update Status</strong><br><span class="small">Picked-up / Delivered</span>
      </a>
    </div>
  </div>

<?php endif; ?>

</body>
</html>