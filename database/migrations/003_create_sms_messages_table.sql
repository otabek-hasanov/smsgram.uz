CREATE TABLE IF NOT EXISTS `sms_messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `gateway_id` INT UNSIGNED DEFAULT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('pending','sent','delivered','failed') NOT NULL DEFAULT 'pending',
    `cost` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `provider_response` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY `sms_messages_user_id_idx` (`user_id`),
    KEY `sms_messages_status_idx` (`status`),
    CONSTRAINT `sms_messages_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `sms_messages_gateway_fk` FOREIGN KEY (`gateway_id`) REFERENCES `gateways` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
