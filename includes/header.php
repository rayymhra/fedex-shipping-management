<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="/index.php">Shipping-MS</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <!-- add links here -->
      </ul>
      <?php if (isLoggedIn()): ?>
         <span class="navbar-text text-light me-3">
            <?= e($_SESSION['role']) ?>
         </span>
         <a class="btn btn-outline-light btn-sm" href="/auth.php?action=logout">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
