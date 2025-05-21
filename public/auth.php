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
  <title>FedEx System Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    .auth-wrapper {
      height: 100vh;
      display: flex;
    }

    .auth-left {
      flex: 1;
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background-color: #fff;
    }

    .auth-right {
      flex: 1;
      background: linear-gradient(135deg, #4e148c, #fe5900);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      padding: 3rem;
    }

    .fedex-logo {
      width: 120px;
      margin-bottom: 1.5rem;
    }

    .login-header {
      font-weight: 700;
      color: #4e148c;
      margin-bottom: 1.5rem;
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

    @media (max-width: 768px) {
      .auth-wrapper {
        flex-direction: column;
      }
      .auth-right {
        display: none;
      }
    }
  </style>
</head>
<body>


<div class="auth-wrapper">

  <div class="auth-left">
    <img src="../assets/Fedex-logo.png" alt="FedEx" class="fedex-logo">
    <h2 class="login-header">Staff Login</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" type="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" type="password" class="form-control" required>
      </div>
      <button class="btn btn-fedex w-100" name="submit">Log In</button>
    </form>
  </div>

  <div class="auth-right">
    <h1 class="mb-4 text-center">Welcome to the FedEx Management System</h1>
    <p style="font-size: 1.2rem; max-width: 400px; text-align: center;">
      Delivering speed, security, and reliability. Every shipment, every login.
    </p>
  </div>

</div>

</body>
</html>