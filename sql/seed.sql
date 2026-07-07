-- Commerce Portal — Seed Data
-- Run after schema.sql or migrations

SET NAMES utf8mb4;

-- -----------------------------------------------------
-- Default roles (required before creating users)
-- -----------------------------------------------------
INSERT INTO `roles` (`name`, `description`, `created_at`, `updated_at`)
VALUES
    ('admin',    'Administrator with full access to the portal', NOW(), NOW()),
    ('customer', 'Standard customer who can purchase products',  NOW(), NOW())
ON DUPLICATE KEY UPDATE
    `description` = VALUES(`description`),
    `updated_at`  = VALUES(`updated_at`);
