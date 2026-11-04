ALTER TABLE `sms_messages` DROP FOREIGN KEY `sms_messages_gateway_fk`;
ALTER TABLE `sms_messages` DROP COLUMN `gateway_id`;
DROP TABLE IF EXISTS `gateways`;

CREATE TABLE IF NOT EXISTS `sms_templates` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `name` VARCHAR(150) NOT NULL,
    `content` TEXT NOT NULL,
    `price` DECIMAL(12,2) NOT NULL DEFAULT 20000.00,
    `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    `provider_template_id` VARCHAR(100) DEFAULT NULL,
    `admin_note` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY `sms_templates_user_id_idx` (`user_id`),
    KEY `sms_templates_status_idx` (`status`),
    CONSTRAINT `sms_templates_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `app_settings` (
    `setting_key` VARCHAR(100) NOT NULL PRIMARY KEY,
    `setting_value` VARCHAR(255) NOT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `app_settings` (`setting_key`, `setting_value`)
SELECT * FROM (SELECT 'template_price' AS setting_key, '20000' AS setting_value) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `app_settings` WHERE `setting_key` = 'template_price') LIMIT 1;

INSERT INTO `app_settings` (`setting_key`, `setting_value`)
SELECT * FROM (SELECT 'price_per_sms' AS setting_key, '65' AS setting_value) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `app_settings` WHERE `setting_key` = 'price_per_sms') LIMIT 1;

ALTER TABLE `sms_messages`
    ADD COLUMN `template_id` INT UNSIGNED NULL AFTER `user_id`,
    ADD COLUMN `recipients` TEXT NULL AFTER `template_id`,
    ADD COLUMN `recipients_count` INT UNSIGNED NOT NULL DEFAULT 1 AFTER `recipients`,
    ADD COLUMN `textup_sms_id` VARCHAR(100) NULL AFTER `recipients_count`;

UPDATE `sms_messages` SET `recipients` = `phone` WHERE `recipients` IS NULL;

ALTER TABLE `sms_messages` DROP COLUMN `phone`;

ALTER TABLE `sms_messages`
    ADD CONSTRAINT `sms_messages_template_fk` FOREIGN KEY (`template_id`) REFERENCES `sms_templates` (`id`) ON DELETE SET NULL;
