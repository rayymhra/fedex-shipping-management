<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();
$conn = db();

if (!isset($_GET['id'])) {
    die("Courier ID missing.");
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM couriers WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Courier not found.");
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $vehicle_type = $_POST['vehicle_type'];
    $capacity_notes = $_POST['capacity_notes'];
    $active = $_POST['active'];

    $update = "UPDATE couriers SET
        name = '$name',
        phone = '$phone',
        vehicle_type = '$vehicle_type',
        capacity_notes = '$capacity_notes',
        active = '$active',
        updated_at = NOW()
        WHERE id = $id";

    if (mysqli_query($conn, $update)) {
        header("Location: couriers.php?success=1");
        exit;
    } else {
        echo "Update failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Courier Info</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include '../includes/header.php'; ?>

  <div class="container mt-4">
    <div class="card">
      <div class="card-header fedex-purple">
        Edit Courier Info
      </div>
      <div class="card-body">
          <form method="POST" class="container">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Vehicle Type</label>
        <input type="text" name="vehicle_type" value="<?= htmlspecialchars($data['vehicle_type']) ?>" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Capacity Notes</label>
        <textarea name="capacity_notes" class="form-control"><?= htmlspecialchars($data['capacity_notes']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Active Status</label>
        <select name="active" class="form-select">
          <option value="1" <?= $data['active'] ? 'selected' : '' ?>>Active</option>
          <option value="0" <?= !$data['active'] ? 'selected' : '' ?>>Inactive</option>
        </select>
      </div>

      <button type="submit" name="submit" class="btn btn-fedex">Update Info</button>
      <a href="couriers.php" class="btn btn-orange">Cancel</a>
    </form>
      </div>
    </div>
    
  </div>
</body>
</html>