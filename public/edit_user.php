<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();
if ($_SESSION['role'] !== 'admin') exit('Only admins allowed');

$conn = db();

// Fetch user by ID
$id = intval($_GET['id'] ?? 0);
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $id AND deleted_at IS NULL"));

if (!$user) {
    exit('User not found');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];
    $updatePassword = !empty($_POST['password']);
    
    if ($updatePassword) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $query = "UPDATE users SET name = '$name', email = '$email', role = '$role', password_hash = '$password' WHERE id = $id";
    } else {
        $query = "UPDATE users SET name = '$name', email = '$email', role = '$role' WHERE id = $id";
    }

    mysqli_query($conn, $query);
    header('Location: users.php?updated=1');
    exit;
}

include '../includes/header.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
  <h3>Edit User</h3>
  <form method="post" class="row g-3 mb-4">
    <div class="col-md-4">
      <label class="form-label">Name</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Role</label>
      <select name="role" class="form-select" required>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
        <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
        <option value="courier" <?= $user['role'] === 'courier' ? 'selected' : '' ?>>Courier</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
      <input type="password" name="password" class="form-control">
    </div>

    <div class="col-md-12">
      <button class="btn btn-success">Update User</button>
      <a href="users.php" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</body>
</html>
