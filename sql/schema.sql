-- Commerce Portal — Full Schema (MySQL 8)
-- Generated to mirror application/migrations/001–014
-- Run after creating the database: CREATE DATABASE commerce_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- roles
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
    `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100)     NOT NULL,
    `description` TEXT             NULL,
    `created_at`  DATETIME         NOT NULL,
    `updated_at`  DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `uq_roles_name` UNIQUE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- users
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id`            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `role_id`       INT UNSIGNED     NOT NULL,
    `name`          VARCHAR(150)     NOT NULL,
    `email`         VARCHAR(255)     NOT NULL,
    `password`      VARCHAR(255)     NOT NULL,
    `status`        ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `last_login_at` DATETIME         NULL,
    `created_at`    DATETIME         NOT NULL,
    `updated_at`    DATETIME         NOT NULL,
    `deleted_at`    DATETIME         NULL,
    PRIMARY KEY (`id`),
    KEY `role_id` (`role_id`),
    KEY `status_deleted_at` (`status`, `deleted_at`),
    CONSTRAINT `uq_users_email` UNIQUE (`email`),
    CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- products
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
    `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `sku`         VARCHAR(50)      NULL,
    `name`        VARCHAR(150)     NOT NULL,
    `description` TEXT             NULL,
    `price`       DECIMAL(12,2)    NOT NULL,
    `status`      ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `created_at`  DATETIME         NOT NULL,
    `updated_at`  DATETIME         NOT NULL,
    `deleted_at`  DATETIME         NULL,
    PRIMARY KEY (`id`),
    KEY `status_deleted_at` (`status`, `deleted_at`),
    CONSTRAINT `uq_products_sku` UNIQUE (`sku`),
    CONSTRAINT `chk_products_price` CHECK (`price` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- orders
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
    `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `user_id`      INT UNSIGNED     NOT NULL,
    `order_number` VARCHAR(50)      NOT NULL,
    `status`       ENUM('pending','paid','failed','cancelled','refunded') NOT NULL DEFAULT 'pending',
    `subtotal`     DECIMAL(12,2)    NOT NULL,
    `tax_amount`   DECIMAL(12,2)    NOT NULL DEFAULT 0.00,
    `total_amount` DECIMAL(12,2)    NOT NULL,
    `currency`     CHAR(3)          NOT NULL DEFAULT 'SGD',
    `created_at`   DATETIME         NOT NULL,
    `updated_at`   DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `status` (`status`),
    KEY `created_at` (`created_at`),
    CONSTRAINT `uq_orders_number` UNIQUE (`order_number`),
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `chk_orders_subtotal` CHECK (`subtotal` >= 0),
    CONSTRAINT `chk_orders_tax_amount` CHECK (`tax_amount` >= 0),
    CONSTRAINT `chk_orders_total_amount` CHECK (`total_amount` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- order_items
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_items` (
    `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `order_id`     BIGINT UNSIGNED  NOT NULL,
    `product_id`   INT UNSIGNED     NOT NULL,
    `product_name` VARCHAR(150)     NOT NULL,
    `unit_price`   DECIMAL(12,2)    NOT NULL,
    `quantity`     INT UNSIGNED     NOT NULL,
    `subtotal`     DECIMAL(12,2)    NOT NULL,
    `created_at`   DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `order_id` (`order_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `chk_order_items_quantity` CHECK (`quantity` > 0),
    CONSTRAINT `chk_order_items_unit_price` CHECK (`unit_price` >= 0),
    CONSTRAINT `chk_order_items_subtotal` CHECK (`subtotal` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- payments
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
    `id`                BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `order_id`          BIGINT UNSIGNED  NOT NULL,
    `payment_reference`  VARCHAR(100)     NOT NULL,
    `provider`           VARCHAR(50)      NOT NULL,
    `provider_reference` VARCHAR(255)     NULL,
    `currency`           CHAR(3)          NOT NULL DEFAULT 'SGD',
    `amount`            DECIMAL(12,2)    NOT NULL,
    `status`            ENUM('pending','paid','failed','refunded','partially_refunded') NOT NULL DEFAULT 'pending',
    `paid_at`           DATETIME         NULL,
    `created_at`        DATETIME         NOT NULL,
    `updated_at`        DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `order_id` (`order_id`),
    KEY `provider` (`provider`),
    KEY `provider_reference` (`provider_reference`),
    KEY `status` (`status`),
    KEY `paid_at` (`paid_at`),
    CONSTRAINT `uq_payments_reference` UNIQUE (`payment_reference`),
    CONSTRAINT `uq_payments_provider_reference` UNIQUE (`provider`, `provider_reference`),
    CONSTRAINT `fk_payments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `chk_payments_amount` CHECK (`amount` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- stripe_transactions
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `stripe_transactions` (
    `id`                    BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `payment_id`            BIGINT UNSIGNED  NOT NULL,
    `stripe_event_id`       VARCHAR(255)     NOT NULL,
    `stripe_payment_intent` VARCHAR(255)     NULL,
    `stripe_session_id`     VARCHAR(255)     NULL,
    `event_type`            VARCHAR(100)     NOT NULL,
    `status`                VARCHAR(50)      NOT NULL,
    `payload`               LONGTEXT         NULL,
    `created_at`            DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `payment_id` (`payment_id`),
    KEY `stripe_event_id` (`stripe_event_id`),
    KEY `stripe_payment_intent` (`stripe_payment_intent`),
    KEY `stripe_session_id` (`stripe_session_id`),
    KEY `event_type` (`event_type`),
    CONSTRAINT `uq_stripe_event_id` UNIQUE (`stripe_event_id`),
    CONSTRAINT `fk_stripe_transactions_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- invoices
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoices` (
    `id`             BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `payment_id`     BIGINT UNSIGNED  NOT NULL,
    `order_id`       BIGINT UNSIGNED  NOT NULL,
    `user_id`        INT UNSIGNED     NOT NULL,
    `invoice_number` VARCHAR(50)      NOT NULL,
    `subtotal`       DECIMAL(12,2)    NOT NULL,
    `tax_amount`     DECIMAL(12,2)    NOT NULL DEFAULT 0.00,
    `total_amount`   DECIMAL(12,2)    NOT NULL,
    `currency`       CHAR(3)          NOT NULL DEFAULT 'SGD',
    `issued_at`      DATETIME         NOT NULL,
    `created_at`     DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `payment_id` (`payment_id`),
    KEY `order_id` (`order_id`),
    KEY `user_id` (`user_id`),
    KEY `issued_at` (`issued_at`),
    CONSTRAINT `uq_invoices_number` UNIQUE (`invoice_number`),
    CONSTRAINT `uq_invoices_payment` UNIQUE (`payment_id`),
    CONSTRAINT `fk_invoices_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_invoices_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_invoices_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `chk_invoices_subtotal` CHECK (`subtotal` >= 0),
    CONSTRAINT `chk_invoices_tax_amount` CHECK (`tax_amount` >= 0),
    CONSTRAINT `chk_invoices_total_amount` CHECK (`total_amount` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- receipts
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `receipts` (
    `id`             BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `payment_id`     BIGINT UNSIGNED  NOT NULL,
    `order_id`       BIGINT UNSIGNED  NOT NULL,
    `user_id`        INT UNSIGNED     NOT NULL,
    `receipt_number` VARCHAR(50)      NOT NULL,
    `amount`         DECIMAL(12,2)    NOT NULL,
    `currency`       CHAR(3)          NOT NULL DEFAULT 'SGD',
    `issued_at`      DATETIME         NOT NULL,
    `created_at`     DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `payment_id` (`payment_id`),
    KEY `order_id` (`order_id`),
    KEY `user_id` (`user_id`),
    KEY `issued_at` (`issued_at`),
    CONSTRAINT `uq_receipts_number` UNIQUE (`receipt_number`),
    CONSTRAINT `uq_receipts_payment` UNIQUE (`payment_id`),
    CONSTRAINT `fk_receipts_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_receipts_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `fk_receipts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `chk_receipts_amount` CHECK (`amount` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- api_tokens
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_tokens` (
    `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `user_id`      INT UNSIGNED     NOT NULL,
    `token_hash`   CHAR(64)         NOT NULL,
    `name`         VARCHAR(100)     NULL,
    `expires_at`   DATETIME         NOT NULL,
    `revoked_at`   DATETIME         NULL,
    `last_used_at` DATETIME         NULL,
    `created_at`   DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `expires_at` (`expires_at`),
    CONSTRAINT `uq_api_tokens_hash` UNIQUE (`token_hash`),
    CONSTRAINT `fk_api_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- audit_logs
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
    `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED     NULL,
    `action`      VARCHAR(100)     NOT NULL,
    `entity_type` VARCHAR(100)     NOT NULL,
    `entity_id`   BIGINT UNSIGNED  NOT NULL,
    `ip_address`  VARCHAR(45)      NULL,
    `user_agent`  TEXT             NULL,
    `created_at`  DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `entity_type_entity_id` (`entity_type`, `entity_id`),
    KEY `created_at` (`created_at`),
    CONSTRAINT `fk_audit_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- invoice_line_items
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `invoice_line_items` (
    `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `invoice_id`   BIGINT UNSIGNED  NOT NULL,
    `product_name` VARCHAR(150)     NOT NULL,
    `unit_price`   DECIMAL(12,2)    NOT NULL,
    `quantity`     INT UNSIGNED     NOT NULL,
    `subtotal`     DECIMAL(12,2)    NOT NULL,
    `created_at`   DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `invoice_id` (`invoice_id`),
    CONSTRAINT `fk_invoice_line_items_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT `chk_invoice_line_items_quantity` CHECK (`quantity` > 0),
    CONSTRAINT `chk_invoice_line_items_unit_price` CHECK (`unit_price` >= 0),
    CONSTRAINT `chk_invoice_line_items_subtotal` CHECK (`subtotal` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- refunds
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `refunds` (
    `id`               BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    `payment_id`       BIGINT UNSIGNED  NOT NULL,
    `stripe_refund_id` VARCHAR(255)     NULL,
    `amount`           DECIMAL(12,2)    NOT NULL,
    `currency`         CHAR(3)          NOT NULL DEFAULT 'SGD',
    `status`           ENUM('pending','succeeded','failed','cancelled') NOT NULL DEFAULT 'pending',
    `reason`           VARCHAR(255)     NULL,
    `created_at`       DATETIME         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `payment_id` (`payment_id`),
    KEY `status` (`status`),
    CONSTRAINT `uq_refunds_stripe_refund_id` UNIQUE (`stripe_refund_id`),
    CONSTRAINT `fk_refunds_payment` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT `chk_refunds_amount` CHECK (`amount` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
