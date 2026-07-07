-- Commerce Portal — Rollback (drops all application tables)
-- WARNING: This permanently deletes all data.

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `refunds`;
DROP TABLE IF EXISTS `invoice_line_items`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `api_tokens`;
DROP TABLE IF EXISTS `receipts`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `stripe_transactions`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `migrations`;

SET FOREIGN_KEY_CHECKS = 1;
