<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();
$conn = db();

$id = $_GET['id'];

$query = "SELECT * FROM addresses WHERE id = $id AND deleted_at IS NULL";
$result = mysqli_query($conn, $query);
$address = mysqli_fetch_assoc($result);

if (!$address) {
    die("Address not found.");
}

if (isset($_POST['submit'])) {
    $label = $_POST['label'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $postal_code = $_POST['postal_code'];
    $country = $_POST['country'];

    $updateQuery = "UPDATE addresses SET label='$label', street='$street', city='$city', province='$province', postal_code='$postal_code', country='$country', updated_at=NOW() WHERE id = $id";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: customer_view.php?id=" . $address['customer_id'] . "&success=address_updated");
        exit;
    } else {
        $error = "Failed to update address.";
    }
}

include "../includes/header.php"
?>

<h2>Edit Address</h2>

<?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>

<form method="post">
  <div class="mb-3">
    <label>Label</label>
    <input type="text" name="label" class="form-control" required value="<?= htmlspecialchars($address['label']) ?>">
  </div>
  <div class="mb-3">
    <label>Street</label>
    <input type="text" name="street" class="form-control" required value="<?= htmlspecialchars($address['street']) ?>">
  </div>
  <div class="mb-3">
    <label>City</label>
    <input type="text" name="city" class="form-control" required value="<?= htmlspecialchars($address['city']) ?>">
  </div>
  <div class="mb-3">
    <label>Province</label>
    <input type="text" name="province" class="form-control" required value="<?= htmlspecialchars($address['province']) ?>">
  </div>
  <div class="mb-3">
    <label>Postal Code</label>
    <input type="text" name="postal_code" class="form-control" required value="<?= htmlspecialchars($address['postal_code']) ?>">
  </div>
  <div class="mb-3">
    <label>Country</label>
    <input type="text" name="country" class="form-control" required value="<?= htmlspecialchars($address['country']) ?>">
  </div>
  <button type="submit" name="submit" class="btn btn-primary">Update Address</button>
  <a href="customer_view.php?id=<?= $address['customer_id'] ?>" class="btn btn-secondary">Cancel</a>
</form>

