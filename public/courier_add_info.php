<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();
$conn = db();


if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $vehicle = $_POST['vehicle_type'];
        $notes = $_POST['capacity_notes'];
        $active = $_POST['active'];

        $query = "INSERT INTO couriers (user_id, name, phone, vehicle_type, capacity_notes, active, created_at)
                  VALUES ('$user_id', '$name', '$phone', '$vehicle', '$notes', '$active', NOW())";

        if (mysqli_query($conn, $query)) {
            header("Location: couriers.php?success=1");
            exit;
        } else {
            echo "Failed to insert courier info: " . mysqli_error($conn);
        }
    }
}

include "../includes/header.php";
?>

<!-- HTML FORM -->
<form method="POST">
  <div class="mb-3">
    <input type="text" name="name" placeholder="Full Name" required class="form-control">
  </div>
  <div class="mb-3">
    <input type="text" name="phone" placeholder="Phone Number" required class="form-control"> 
  </div>
  <div class="mb-3">
    <input type="text" name="vehicle_type" placeholder="Vehicle Type" class="form-control">
  </div>
  <div class="mb-3">
    <textarea name="capacity_notes" placeholder="Notes..." class="form-control"></textarea>
  </div>
  <div class="mb-3">
    <select name="active" class="form-select">
        <option value="1">Active</option>
        <option value="0">Inactive</option>
    </select>
  </div>
    
  
    <button type="submit" name="submit" class="btn btn-primary">Save Courier Info</button>
</form>
