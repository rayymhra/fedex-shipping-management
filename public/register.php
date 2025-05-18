<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $pdo  = pdo();
    $stmt = $pdo->prepare("INSERT INTO users (role, name, email, password_hash)
                           VALUES ('admin', ?, ?, ?)");
    $stmt->execute([$name, $email, $hash]);
    echo "Admin created. <a href='auth.php'>Go to login</a>";
    exit;
}
?>
<!-- simple Bootstrap form -->
<!doctype html>
<html lang="en"><head><link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"></head>
<body class="container mt-5">
<h2>Create First Admin</h2>
<form method="post" class="w-50">
  <div class="mb-2"><label class="form-label">Name</label>
    <input class="form-control" name="name" required></div>
  <div class="mb-2"><label class="form-label">Email</label>
    <input class="form-control" type="email" name="email" required></div>
  <div class="mb-3"><label class="form-label">Password</label>
    <input class="form-control" type="password" name="password" required></div>
  <button class="btn btn-primary">Create Admin</button>
</form>
</body></html>
