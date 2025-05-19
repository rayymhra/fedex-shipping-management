<?php
require '../includes/db.php';
require '../includes/functions.php';

startSession();
$conn   = db();
$action = $_GET['action'] ?? 'login';


if ($action === 'logout') {
    logoutUser();
    header('Location: auth.php');
    exit;
}


$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    $result = mysqli_query(
        $conn,
        "SELECT id, role, password_hash FROM users
         WHERE email = '$email' AND active = 1 AND deleted_at IS NULL LIMIT 1"
    );
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($pass, $user['password_hash'])) {
        loginUser($user['id'], $user['role']);
        header('Location: index.php');
        exit;
    }
    $error = 'Wrong email or password';
}

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <title>Login</title>
</head>
<body class="container mt-5">
  <h2 class="mb-4">Staff Login</h2>

  <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="post" class="w-50">
      <div class="mb-3">
          <label class="form-label">Email</label>
          <input name="email" type="email" class="form-control" required>
      </div>
      <div class="mb-3">
          <label class="form-label">Password</label>
          <input name="password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-primary" name="submit">Log In</button>
  </form>
</body>
</html>