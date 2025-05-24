<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

startSession();
requireLogin();

$conn         = db();
$shipment_id  = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 1) Validate shipment ID
if ($shipment_id <= 0) {
    echo "Invalid shipment ID.";
    exit;
}

// Allowed statuses must match your ENUM exactly:
$allowed_statuses = ['pending','picked_up','in_transit','delivered','cancelled'];

// 2) Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Grab & validate status
    // $status = $_POST['status'] ?? '';
    // if (! in_array($status, $allowed_statuses, true)) {
    //     die("Invalid status value.");
    // }

    // Grab checkpoint fields (basic trim + escape)
    $checkpoint_location = mysqli_real_escape_string($conn, trim($_POST['checkpoint_location'] ?? ''));
    $checkpoint_note     = mysqli_real_escape_string($conn, trim($_POST['checkpoint_note'] ?? ''));
    $timestamp           = date('Y-m-d H:i:s');

    // Update shipment status
    // $upd = "UPDATE shipments 
    //         SET status = '$status',
    //             updated_at = NOW()
    //         WHERE id = $shipment_id";
    // mysqli_query($conn, $upd) or die("DB error: " . mysqli_error($conn));

    // Insert new checkpoint
    $ins = "INSERT INTO tracking
            (shipment_id, checkpoint_location, checkpoint_note, checkpoint_time)
            VALUES
            ($shipment_id, '$checkpoint_location', '$checkpoint_note', '$timestamp')";
    mysqli_query($conn, $ins) or die("DB error: " . mysqli_error($conn));

    // Redirect back with a flag
    header("Location: shipment_tracking.php?id=$shipment_id&added=1");
    exit;
}

// 3) Fetch shipment & checkpoints
$shipment   = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM shipments WHERE id = $shipment_id")
);
$checkpoints = mysqli_query(
    $conn,
    "SELECT * FROM tracking
     WHERE shipment_id = $shipment_id 
     ORDER BY checkpoint_time DESC"
);

include "../includes/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Track Shipment <?= htmlspecialchars($shipment['tracking_code']) ?></title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    >
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light">
<div class="container py-5">

    <!-- Header & Back -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Track: <?= htmlspecialchars($shipment['tracking_code']) ?></h2>
        <div>
            <a href="shipments.php" class="btn btn-orange">Back to List</a>
            <!-- <a href="shipment_view.php?id=<?= $shipment_id ?>" class="btn btn-outline-primary">View Details</a> -->
        </div>
    </div>

    <!-- Success Alert -->
    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success">Checkpoint added successfully!</div>
    <?php endif; ?>

    <!-- Update Form -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header fedex-purple">
            Add Checkpoint
        </div>
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <!-- <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <?php foreach ($allowed_statuses as $s): ?>
                            <option value="<?= $s ?>"
                                <?= $shipment['status'] === $s ? 'selected' : '' ?>>
                                <?= ucwords(str_replace('_',' ',$s)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select> -->

                    <label class="form-label">Location</label>
                    <input
                      type="text"
                      name="checkpoint_location"
                      class="form-control"
                      placeholder="e.g. Warehouse Jakarta"
                      required
                    >
                </div>
                <!-- <div class="col-md-4">
                    
                </div> -->
                <div class="col-md-5">
                    <label class="form-label">Note</label>
                    <input
                      type="text"
                      name="checkpoint_note"
                      class="form-control"
                      placeholder="e.g. Departed facility"
                    >
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-fedex mt-4">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Checkpoint History -->
    <div class="card shadow-sm">
        <div class="card-header fedex-purple">
            Checkpoint History
        </div>
        <div class="card-body">
            <h5 class="card-title"></h5>
            <?php if (mysqli_num_rows($checkpoints) > 0): ?>
                <ul class="list-group">
                    <?php while ($cp = mysqli_fetch_assoc($checkpoints)): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong><?= htmlspecialchars($cp['checkpoint_time']) ?></strong><br>
                                    <em><?= htmlspecialchars($cp['checkpoint_location']) ?></em>
                                </div>
                                <small><?= htmlspecialchars($cp['checkpoint_note']) ?></small>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p class="text-muted mb-0">No checkpoints recorded yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
