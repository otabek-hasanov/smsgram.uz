CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `checkout_payment_id` INT UNSIGNED DEFAULT NULL,
    `checkout_uuid` VARCHAR(64) DEFAULT NULL,
    `amount` DECIMAL(12,2) NOT NULL,
    `status` ENUM('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
    `payment_url` VARCHAR(500) DEFAULT NULL,
    `paid_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY `payments_user_id_idx` (`user_id`),
    KEY `payments_checkout_payment_id_idx` (`checkout_payment_id`),
    CONSTRAINT `payments_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
