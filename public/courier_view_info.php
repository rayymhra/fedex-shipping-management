<?php
    require '../includes/db.php';
    require '../includes/functions.php';
    startSession();
    requireLogin();

    // requireAdmin();
    $conn = db();
    if (! isset($_GET['id'])) {
        die("Courier ID missing.");
    }

    $id    = $_GET['id'];
    $query = "SELECT 
            couriers.*, 
            users.name AS user_name, 
            users.email 
          FROM couriers 
          JOIN users ON couriers.user_id = users.id 
          WHERE couriers.id = $id";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    if (! $data) {
        die("Courier info not found.");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Courier Info</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include '../includes/header.php'; ?>

  <div class="container mt-4">
    <h2 class="mb-4">Courier Info</h2>

    <div class="card shadow">
      <div class="card-body">
        <h5 class="card-title">
  <?php echo htmlspecialchars($data['user_name'])?> (<?php echo htmlspecialchars($data['name'])?>)
</h5>
        <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($data['email'])?></p>
        <p class="card-text"><strong>Phone:</strong> <?php echo htmlspecialchars($data['phone'])?></p>
        <p class="card-text"><strong>Vehicle Type:</strong> <?php echo htmlspecialchars($data['vehicle_type'])?></p>
        <p class="card-text"><strong>Capacity Notes:</strong> <?php echo nl2br(htmlspecialchars($data['capacity_notes']))?></p>
        <p class="card-text">
          <strong>Status:</strong>
          <?php if ($data['active']): ?>
            <span class="badge bg-success">Active</span>
          <?php else: ?>
            <span class="badge bg-secondary">Inactive</span>
          <?php endif; ?>
        </p>
        <a href="couriers.php" class="btn btn-secondary mt-3">← Back to Management</a>
      </div>
    </div>
  </div>
</body>
</html>