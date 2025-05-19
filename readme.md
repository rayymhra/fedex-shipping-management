<div align="center">
  <h1>Documentation</h1>
  <strong>website assignment - shipping management system</strong><br>
  <strong>tables required: shipments, customers, addresses, couriers, tracking</strong>
</div>
<br>

# features:
[x] auth
[x] dashboard / navbar
- admin
[x] manage users
[] courier management (add / retire courier)
[] customer list
[] reports (Analytics & KPIs)
- staff
[] customer management (Create / edit customers)
[] address management (maybe put it together in the customer management)
[] New shipment, assign courier
[] tracking (Update shipment status)
[] courier list
-courier
[] my shipments (see jobs that are assigned to them)
[] update status (Picked-up / Delivered)

[] trash (optional, if i had the time)

<br>

# Databases:
-- 1. users
CREATE TABLE users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role            ENUM('admin','staff','courier') NOT NULL DEFAULT 'staff',
    name            VARCHAR(120)            NOT NULL,
    email           VARCHAR(180)            NOT NULL UNIQUE,
    password_hash   CHAR(255)               NOT NULL,
    active          TINYINT(1)              NOT NULL DEFAULT 1,
    created_at      TIMESTAMP               DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP               DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP               NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2. customers 
CREATE TABLE customers (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(150)            NOT NULL,
    email           VARCHAR(180)            NULL UNIQUE,
    phone           VARCHAR(40)             NULL,
    notes           TEXT                    NULL,
    created_at      TIMESTAMP               DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP               DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP               NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 3. addresses
CREATE TABLE addresses (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    customer_id     INT UNSIGNED            NOT NULL,
    label           VARCHAR(60)             NOT NULL,         -- e.g. "Warehouse", "Home"
    street          VARCHAR(255)            NOT NULL,
    city            VARCHAR(120)            NOT NULL,
    province        VARCHAR(120)            NOT NULL,
    postal_code     VARCHAR(20)             NOT NULL,
    country         VARCHAR(120)            NOT NULL DEFAULT 'Indonesia',
    created_at      TIMESTAMP               DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP               DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP               NULL,
    CONSTRAINT fk_addr_customer FOREIGN KEY (customer_id)
        REFERENCES customers(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 4. couriers 
CREATE TABLE couriers (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(120)            NOT NULL,
    phone           VARCHAR(40)             NULL,
    vehicle_type    VARCHAR(60)             NULL,             -- "Motorbike", "Van", etc.
    capacity_notes  VARCHAR(120)            NULL,
    active          TINYINT(1)              NOT NULL DEFAULT 1,
    created_at      TIMESTAMP               DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP               DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP               NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 5. shipments 
CREATE TABLE shipments (
    id                      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tracking_code           CHAR(12)                NOT NULL UNIQUE,
    customer_id             INT UNSIGNED            NOT NULL,
    sender_address_id       INT UNSIGNED            NOT NULL,
    recipient_address_id    INT UNSIGNED            NOT NULL,
    courier_id              INT UNSIGNED            NULL,
    weight_kg               DECIMAL(8,2)            NOT NULL,
    dimensions_cm           VARCHAR(50)             NULL,      -- e.g. "30x25x20"
    price_idr               DECIMAL(12,2)           NOT NULL,
    status                  ENUM('pending','picked_up','in_transit','delivered','cancelled')
                                                    NOT NULL DEFAULT 'pending',
    expected_delivery_date  DATE                    NULL,
    notes                   TEXT                    NULL,
    created_at              TIMESTAMP               DEFAULT CURRENT_TIMESTAMP,
    updated_at              TIMESTAMP               DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at              TIMESTAMP               NULL,
    CONSTRAINT fk_ship_customer    FOREIGN KEY (customer_id)          REFERENCES customers(id),
    CONSTRAINT fk_ship_sender      FOREIGN KEY (sender_address_id)    REFERENCES addresses(id),
    CONSTRAINT fk_ship_recipient   FOREIGN KEY (recipient_address_id) REFERENCES addresses(id),
    CONSTRAINT fk_ship_courier     FOREIGN KEY (courier_id)           REFERENCES couriers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 6. tracking  (history timeline for each shipment)
CREATE TABLE tracking (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    shipment_id     INT UNSIGNED            NOT NULL,
    status          ENUM('picked_up','in_transit','delivered','issue','cancelled')
                                            NOT NULL,
    location        VARCHAR(150)            NULL,
    remarks         VARCHAR(255)            NULL,
    created_at      TIMESTAMP               DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_track_ship FOREIGN KEY (shipment_id)
        REFERENCES shipments(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
