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
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    .dashboard-header {
      color: #4e148c;
    }

    .card-custom {
      border-left: 5px solid #4e148c;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.06);
      transition: 0.3s ease;
    }


    .btn-fedex {
      background-color: #4e148c;
      color: #fff;
      border: none;
    }

    .btn-fedex:hover {
      background-color: #3a0f6c;
      color: white;
    }

    .btn-orange {
      background-color: #fe5900;
      color: #fff;
      border: none;
    }

    .btn-orange:hover {
      background-color: #e14f00;
    }
  </style>
</head>
<body>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="dashboard-header">Dashboard – <?= ucfirst($role) ?></h2>
    <!-- <a href="../logout.php" class="btn btn-outline-secondary">Logout</a> -->
  </div>

  <?php if ($role === 'admin'): ?>
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card card-custom p-4">
          <h5 class="mb-2">Manage Users</h5>
          <p class="text-muted">Admins, staff, couriers</p>
          <a href="users.php" class="btn btn-fedex w-100">Go</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-custom p-4">
          <h5 class="mb-2">Couriers</h5>
          <p class="text-muted">Add / retire couriers</p>
          <a href="couriers.php" class="btn btn-fedex w-100">Go</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-custom p-4">
          <h5 class="mb-2">Customers</h5>
          <p class="text-muted">Full customer list</p>
          <a href="customers.php" class="btn btn-fedex w-100">Go</a>
        </div>
      </div>
    </div>

    <?php
      $shipmentsToday = mysqli_fetch_row(
        mysqli_query($conn, "SELECT COUNT(*) FROM shipments WHERE DATE(created_at)=CURDATE()")
      )[0];
    ?>
    <div class="alert alert-light">
       <strong><?= $shipmentsToday ?></strong> shipments created today
    </div>

  <?php elseif ($role === 'staff'): ?>
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card card-custom p-4">
          <h5>Customers</h5>
          <p class="text-muted">Create / edit customers</p>
          <a href="customers.php" class="btn btn-fedex w-100">Go</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-custom p-4">
          <h5>Shipments</h5>
          <p class="text-muted">New shipment, assign courier</p>
          <a href="shipments.php" class="btn btn-fedex w-100">Go</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-custom p-4">
          <h5>Add Tracking</h5>
          <p class="text-muted">Update shipment status</p>
          <a href="tracking.php" class="btn btn-fedex w-100">Go</a>
        </div>
      </div>
    </div>

  <?php else: // courier ?>
    <div class="row g-4 mb-4">
      <div class="col-md-6">
        <div class="card card-custom p-4">
          <h5>My Shipments</h5>
          <p class="text-muted">Jobs assigned to me</p>
          <a href="my_shipments.php" class="btn btn-orange w-100">Go</a>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-custom p-4">
          <h5>Update Status</h5>
          <p class="text-muted">Picked-up / Delivered</p>
          <a href="tracking.php" class="btn btn-orange w-100">Go</a>
        </div>
      </div>
    </div>
  <?php endif; ?>

</body>
</html>
