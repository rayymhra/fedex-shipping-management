<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// require_once 'auth.php';
$conn = db();



// Simple tracking code generator (e.g., 12 uppercase letters/digits)
function generateTrackingCode($length = 12) {
    return strtoupper(bin2hex(random_bytes($length / 2)));
}

$customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
$sender_address_id = isset($_POST['sender_address_id']) ? intval($_POST['sender_address_id']) : 0;

$recipient_label = isset($_POST['recipient_label']) ? trim($_POST['recipient_label']) : '';
$recipient_street = isset($_POST['recipient_street']) ? trim($_POST['recipient_street']) : '';
$recipient_city = isset($_POST['recipient_city']) ? trim($_POST['recipient_city']) : '';
$recipient_province = isset($_POST['recipient_province']) ? trim($_POST['recipient_province']) : '';
$recipient_postal_code = isset($_POST['recipient_postal_code']) ? trim($_POST['recipient_postal_code']) : '';
$recipient_country = isset($_POST['recipient_country']) ? trim($_POST['recipient_country']) : '';

$weight_kg = isset($_POST['weight_kg']) ? floatval($_POST['weight_kg']) : 0;
$dimensions_cm = isset($_POST['dimensions_cm']) ? trim($_POST['dimensions_cm']) : '';
$price_idr = isset($_POST['price_idr']) ? floatval($_POST['price_idr']) : 0;
$expected_delivery_date = isset($_POST['expected_delivery_date']) ? $_POST['expected_delivery_date'] : '';
$courier_id = isset($_POST['courier_id']) ? intval($_POST['courier_id']) : 0;
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

// Basic validation
if ($customer_id <= 0 || $sender_address_id <= 0 || 
    empty($recipient_label) || empty($recipient_street) || empty($recipient_city) || empty($recipient_province) || empty($recipient_postal_code) || empty($recipient_country) ||
    $weight_kg <= 0 || $price_idr <= 0 || $courier_id <= 0) {
    echo "Please fill in all required fields correctly.";
    exit;
}

// Escape strings to prevent SQL errors (basic escaping)
$recipient_label = mysqli_real_escape_string($conn, $recipient_label);
$recipient_street = mysqli_real_escape_string($conn, $recipient_street);
$recipient_city = mysqli_real_escape_string($conn, $recipient_city);
$recipient_province = mysqli_real_escape_string($conn, $recipient_province);
$recipient_postal_code = mysqli_real_escape_string($conn, $recipient_postal_code);
$recipient_country = mysqli_real_escape_string($conn, $recipient_country);
$dimensions_cm = mysqli_real_escape_string($conn, $dimensions_cm);
$expected_delivery_date = mysqli_real_escape_string($conn, $expected_delivery_date);
$notes = mysqli_real_escape_string($conn, $notes);

// Generate tracking code
$tracking_code = generateTrackingCode();

$sql = "INSERT INTO shipments (
    customer_id,
    sender_address_id,
    recipient_label,
    recipient_street,
    recipient_city,
    recipient_province,
    recipient_postal_code,
    recipient_country,
    weight_kg,
    dimensions_cm,
    price_idr,
    expected_delivery_date,
    courier_id,
    notes,
    tracking_code,
    created_at
)
 VALUES (
    $customer_id,
    $sender_address_id,
    '$recipient_label',
    '$recipient_street',
    '$recipient_city',
    '$recipient_province',
    '$recipient_postal_code',
    '$recipient_country',
    $weight_kg,
    '$dimensions_cm',
    $price_idr,
    " . ($expected_delivery_date ? "'$expected_delivery_date'" : "NULL") . ",
    $courier_id,
    '$notes',
    '$tracking_code',
    NOW()
)";

if (mysqli_query($conn, $sql)) {
    header("Location: shipments.php?success=1");
    exit;
} else {
    echo "Error saving shipment: " . mysqli_error($conn);
}