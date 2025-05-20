<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

startSession();
requireLogin();
$conn = db();

$customers = mysqli_query($conn, "SELECT id, name FROM customers WHERE deleted_at IS NULL");


$couriers = mysqli_query($conn, "SELECT couriers.id, users.name FROM couriers 
    JOIN users ON couriers.user_id = users.id 
    WHERE users.deleted_at IS NULL");

include "../includes/header.php"
?>
<div class="container mt-5">
    <h2>Create New Shipment</h2>
    <form action="shipment_store.php" method="post">
        <!-- Customer -->
        <div class="mb-3">
            <label for="customer" class="form-label">Customer</label>
            <select name="customer_id" id="customer" class="form-select" onchange="loadAddresses(this.value)" required>
                <option value="">-- Select Customer --</option>
                <?php while ($c = mysqli_fetch_assoc($customers)): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Sender Address -->
        <div class="mb-3">
            <label for="sender" class="form-label">Sender Address</label>
            <select name="sender_address_id" id="sender" class="form-select" required>
                <option value="">-- Select Sender Address --</option>
            </select>
        </div>

        <!-- Recipient Address -->
        <div class="mb-3">
            <div class="mb-3">
    <label class="form-label">Recipient Address</label>
    <input type="text" name="recipient_label" class="form-control mb-2" placeholder="Label (e.g. Home, Office)" required>
    <input type="text" name="recipient_street" class="form-control mb-2" placeholder="Street" required>
    <input type="text" name="recipient_city" class="form-control mb-2" placeholder="City" required>
    <input type="text" name="recipient_province" class="form-control mb-2" placeholder="Province" required>
    <input type="text" name="recipient_postal_code" class="form-control mb-2" placeholder="Postal Code" required>
    <input type="text" name="recipient_country" class="form-control" placeholder="Country" required>
</div>
        </div>

        <!-- Weight -->
        <div class="mb-3">
            <label for="weight_kg" class="form-label">Weight (kg)</label>
            <input type="number" step="0.01" name="weight_kg" class="form-control" required>
        </div>

        <!-- Dimensions -->
        <div class="mb-3">
            <label for="dimensions_cm" class="form-label">Dimensions (cm)</label>
            <input type="text" name="dimensions_cm" class="form-control" placeholder="e.g. 30x20x10">
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label for="price_idr" class="form-label">Price (IDR)</label>
            <input type="number" name="price_idr" class="form-control" required>
        </div>

        <!-- Expected Delivery -->
        <div class="mb-3">
            <label for="expected_delivery_date" class="form-label">Expected Delivery Date</label>
            <input type="date" name="expected_delivery_date" class="form-control">
        </div>

        <!-- Courier -->
        <div class="mb-3">
            <label for="courier_id" class="form-label">Assign Courier</label>
            <select name="courier_id" class="form-select" required>
                <option value="">-- Select Courier --</option>
                <?php while ($cr = mysqli_fetch_assoc($couriers)): ?>
                    <option value="<?= $cr['id'] ?>"><?= htmlspecialchars($cr['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Notes -->
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary">Create Shipment</button>
    </form>
</div>

<script>
function loadAddresses(customerId) {
    if (!customerId) return;

    fetch(`fetch_addresses.php?customer_id=${customerId}`)
        .then(res => res.json())
        .then(data => {
            let sender = document.getElementById('sender');

            sender.innerHTML = '<option value="">-- Select Sender Address --</option>';

            data.forEach(addr => {
                const text = `${addr.label} - ${addr.street}, ${addr.city}, ${addr.province}, ${addr.postal_code}, ${addr.country}`;
                const option = new Option(text, addr.id);
                sender.appendChild(option);
            });
        })
        .catch(err => console.error('Error fetching addresses:', err));
}

</script>
<?php include '../includes/footer.php'; ?>