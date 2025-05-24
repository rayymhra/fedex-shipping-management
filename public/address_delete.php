<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();
$conn = db();
$id = $_GET['id'];

// Soft delete by setting deleted_at timestamp
$query = "UPDATE addresses SET deleted_at = NOW() WHERE id = $id";
mysqli_query($conn, $query);

header("Location: customer_management.php?success=address_deleted");
exit;