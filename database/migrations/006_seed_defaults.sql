INSERT INTO `gateways` (`name`, `slug`, `base_uri`, `price_per_sms`, `status`)
SELECT * FROM (SELECT 'Eskiz' AS name, 'eskiz' AS slug, 'https://notify.eskiz.uz/api/' AS base_uri, 65.00 AS price_per_sms, 'active' AS status) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `gateways` WHERE `slug` = 'eskiz') LIMIT 1;

INSERT INTO `gateways` (`name`, `slug`, `base_uri`, `price_per_sms`, `status`)
SELECT * FROM (SELECT 'PlayMobile' AS name, 'playmobile' AS slug, 'https://send.smsxabar.uz/broker-api/' AS base_uri, 60.00 AS price_per_sms, 'active' AS status) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM `gateways` WHERE `slug` = 'playmobile') LIMIT 1;
