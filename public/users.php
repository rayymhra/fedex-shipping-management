<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();
if ($_SESSION['role'] !== 'admin') { exit('Only admins allowed'); }

$conn = db();

if (isset($_POST['add_user'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $role  = $_POST['role'];          // admin | staff | courier
    $pass  = password_hash($_POST['password'], PASSWORD_BCRYPT);

    mysqli_query(
        $conn,
        "INSERT INTO users (role, name, email, password_hash, active)
         VALUES('$role', '$name', '$email', '$pass', 1)"
    );
    header('Location: users.php?success=1'); exit;
}


if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $id LIMIT 1");
    header('Location: users.php'); exit;
}

$list = mysqli_query($conn, "SELECT id, name, email, role FROM users WHERE deleted_at IS NULL");

include "../includes/header.php"
?>
<!doctype html>
<html lang="en">
<head>
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <title>User Management</title>
</head>
<body>

<h3>Add New User</h3>
<form method="post" class="row g-2 mb-4">
  <input type="hidden" name="add_user">
  <div class="col-md-3"><input class="form-control" name="name"  placeholder="Name"  required></div>
  <div class="col-md-3"><input class="form-control" name="email" type="email" placeholder="Email" required></div>
  <div class="col-md-2">
     <select class="form-select" name="role">
        <option value="staff">Staff</option>
        <option value="courier">Courier</option>
        <option value="admin">Admin</option>
     </select>
  </div>
  <div class="col-md-2"><input class="form-control" name="password" type="password" placeholder="Password" required></div>
  <div class="col-md-2"><button class="btn btn-primary w-100">Add</button></div>
</form>

<h3>Existing Users</h3>
<table class="table table-bordered">
  <thead><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr></thead>
  <tbody>
    <?php while ($u = mysqli_fetch_assoc($list)): ?>
      <tr>
        <td><?= $u['name'] ?></td>
        <td><?= $u['email'] ?></td>
        <td><?= $u['role'] ?></td>
        <td><a class="btn btn-sm btn-danger"
               href="users.php?del=<?= $u['id'] ?>"
               onclick="return confirm('Delete this user?')">Del</a></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

</body></html>