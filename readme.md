<div align="center">
  <h1>Documentation</h1>
  <strong>website assignment - shipping management system</strong><br>
  <strong>tables required: shipments, customers, addresses, couriers, tracking</strong>
</div>
<br>

# features:
[v] auth  <br>
[v] dashboard / navbar <br>
- admin <br>
[v] manage users -> users.php (no edit for now)<br>
[v] courier management (add / retire courier) ->  couriers.php, courier_add_info.php, courier_edit_info.php, courier_view_info.php<br>
[v] customer list -> customers.php<br>
[] reports (Analytics & KPIs) -> admin_reports.php<br>
- staff <br>
[v] customer management (Create / edit customers) -> customer_management<br>
[v] address management (maybe put it together in the customer management) -> address_add.php, address_delete.php, address_edit.php<br>
[v] shipments(create & update status, print labels, assign courier) <br>
[v] tracking (Update shipment status, add checkpoints) <br>
[] courier list <br>
-courier <br>
[v] my shipments (see jobs that are assigned to them)<br>
[v] update status (Picked-up / Delivered) <br>
<br>


# ui/ux
[] notif sweetalert <br>
[] check all footer <br>
[] check all require input validation <br>

# Databases:

-- triggers to auto-roll shipment.status
DELIMITER $$
CREATE TRIGGER trg_tracking_after_insert
AFTER INSERT ON tracking
FOR EACH ROW
BEGIN
    UPDATE shipments
    SET status = NEW.status,
        updated_at = NOW()
    WHERE id = NEW.shipment_id;
END$$
DELIMITER ;
