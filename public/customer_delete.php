<?php
require '../includes/db.php';
require '../includes/functions.php';
startSession();
requireLogin();

// requireAdmin();
$conn = db();

if (!isset($_GET['id'])) {
    header("Location: customer_management.php");
    exit;
}

$id = $_GET['id'];
$query = "UPDATE customers SET deleted_at = NOW() WHERE id = $id";

if (mysqli_query($conn, $query)) {
    header("Location: customer_management.php?success=Customer deleted successfully");
} else {
    header("Location: customer_management.php?error=Failed to delete customer");
}
exit;