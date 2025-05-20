<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();

// requireAdmin();
$conn = db();
$customer_id = $_GET['customer_id'];

if (isset($_POST['submit'])) {
    $label = $_POST['label'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];

    $query = "INSERT INTO addresses (customer_id, label, street, city, province, postal_code, country, created_at) VALUES ($customer_id, '$label', '$street', '$city', '$province', '$postal_code', '$country', NOW())";

    if (mysqli_query($conn, $query)) {
        header("Location: customer_view.php?id=$customer_id&success=address_added");
        exit;
    } else {
        $error = "Failed to add address.";
    }
}
include "../includes/header.php"

?>

<h2>Add Address for Customer #<?= htmlspecialchars($customer_id) ?></h2>

<?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>

<form method="post">
  <div class="mb-3">
    <label>Label (Home, Office, etc.)</label>
    <input type="text" name="label" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Street</label>
    <input type="text" name="street" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>City</label>
    <input type="text" name="city" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Province</label>
    <input type="text" name="province" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Postal Code</label>
    <input type="text" name="postal_code" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Country</label>
    <input type="text" name="country" class="form-control" required>
  </div>
  <button type="submit" name="submit" class="btn btn-success">Add Address</button>
  <a href="customer_view.php?id=<?= $customer_id ?>" class="btn btn-secondary">Cancel</a>
</form>