-- ============================================================
--  SQL Schema – Booking Barbershop
--  Database : barbershop_db
--  Charset  : utf8mb4 / utf8mb4_unicode_ci
--  Notes    : All tables use soft-delete (deleted_at)
-- ============================================================

CREATE DATABASE IF NOT EXISTS barbershop_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE barbershop_db;

-- ------------------------------------------------------------
-- 1. users
--    Stores login credentials + role (Admin / User)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    username     VARCHAR(50)     NOT NULL UNIQUE,
    password     VARCHAR(255)    NOT NULL,           -- bcrypt / password_hash()
    status       ENUM('Admin','User') NOT NULL DEFAULT 'User',
    created_at   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at   TIMESTAMP       NULL     DEFAULT NULL,  -- soft delete
    PRIMARY KEY (id),
    INDEX idx_users_username   (username),
    INDEX idx_users_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. services
--    Barbershop service catalogue (haircut, shave, etc.)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS services (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    name            VARCHAR(100)    NOT NULL,
    description     TEXT            NULL,
    price           INT UNSIGNED    NOT NULL DEFAULT 0,   -- IDR, no decimals
    duration_minute TINYINT UNSIGNED NOT NULL DEFAULT 30, -- estimated duration
    is_active       TINYINT(1)      NOT NULL DEFAULT 1,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP       NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    INDEX idx_services_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. bookings
--    Customer appointment records
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS bookings (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    user_id         INT UNSIGNED    NOT NULL,
    service_id      INT UNSIGNED    NOT NULL,
    customer_name   VARCHAR(100)    NOT NULL,
    booking_date    DATE            NOT NULL,
    booking_time    TIME            NOT NULL,
    notes           TEXT            NULL,
    status          ENUM('Pending','Confirmed','Done','Cancelled') NOT NULL DEFAULT 'Pending',
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at      TIMESTAMP       NULL     DEFAULT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_bookings_user    FOREIGN KEY (user_id)    REFERENCES users(id)    ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_bookings_service FOREIGN KEY (service_id) REFERENCES services(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_bookings_user_id    (user_id),
    INDEX idx_bookings_service_id (service_id),
    INDEX idx_bookings_date       (booking_date),
    INDEX idx_bookings_deleted_at (deleted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  SEED DATA
-- ============================================================

-- Users
--  Passwords below are bcrypt hashes of the plaintext shown in the comment.
--  Generate fresh hashes in PHP:  password_hash('yourpass', PASSWORD_BCRYPT)
INSERT INTO users (username, password, status) VALUES
('admin',  '$2y$10$srcPlwMpb8PiSOBcPQpgS.4bVGzl3Js2MzbTquzuw47npXs4fVjRi', 'Admin'), -- admin123
('barber1','$2y$10$p7InDM6PIfKslJ5sa/6BJeeuNkyoL3O0jXJ4UNXul41639MFSynH6', 'User');  -- user123

-- Services
INSERT INTO services (name, description, price, duration_minute) VALUES
('Haircut Regular',  'Standard haircut with scissors or clipper',        35000, 30),
('Haircut + Wash',   'Haircut including hair wash and blow dry',          50000, 45),
('Beard Trim',       'Neat beard shaping and trimming',                   25000, 20),
('Clean Shave',      'Full facial shave with warm towel treatment',       40000, 30),
('Hair Color',       'Single-color hair dyeing with quality product',    150000, 90),
('Hair Cream Bath',  'Deep conditioning treatment for healthy hair',      75000, 60);

-- Bookings (sample data)
INSERT INTO bookings (user_id, service_id, customer_name, booking_date, booking_time, notes, status) VALUES
(2, 1, 'Budi Santoso',   '2026-06-05', '09:00:00', NULL,                   'Confirmed'),
(2, 3, 'Rizky Pratama',  '2026-06-05', '10:00:00', 'Prefer neat trim',     'Pending'),
(1, 2, 'Deni Kurniawan', '2026-06-06', '13:00:00', NULL,                   'Pending'),
(2, 5, 'Andi Wijaya',    '2026-06-06', '14:30:00', 'Dark brown color',     'Confirmed'),
(1, 4, 'Fajar Nugroho',  '2026-06-07', '11:00:00', NULL,                   'Done'),
(2, 6, 'Hendra Saputra', '2026-06-03', '16:00:00', 'Scalp feels dry',     'Cancelled');
