<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = $_SESSION['role'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Fedex Shipment Management</title>
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <!-- Logo and Brand -->
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="../assets/logo.png" alt="Logo" width="160" class="d-inline-block align-text-top me-2">
    </a>

    <!-- Toggler for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links -->
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto">
        <?php if ($role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
          <li class="nav-item"><a class="nav-link" href="couriers.php">Couriers</a></li>
          <li class="nav-item"><a class="nav-link" href="customers.php">Customers</a></li>
          <!-- <li class="nav-item"><a class="nav-link" href="admin_reports.php">Reports</a></li> -->
        <?php endif; ?>

        <?php if ($role === 'staff'): ?>
          <li class="nav-item"><a class="nav-link" href="customer_management.php">Customers</a></li>
          <li class="nav-item"><a class="nav-link" href="shipments.php">Shipments</a></li>
        <?php endif; ?>

        <?php if ($role === 'courier'): ?>
          <li class="nav-item"><a class="nav-link" href="my_shipments.php">Update Status</a></li>
        <?php endif; ?>
      </ul>

      <?php if ($role): ?>
        <span class="navbar-text text-light me-3"><?= ucfirst($role) ?></span>
        <a class="btn btn-orange" href="../public/auth.php?action=logout">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>


<div class="container py-4">
