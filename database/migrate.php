<?php

/*
|--------------------------------------------------------------------------
| Migratsiya runner
|--------------------------------------------------------------------------
| Ishlatish: php database/migrate.php
*/

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    die('Forbidden: faqat CLI orqali ishga tushiriladi.');
}

define('SMSGRAM', dirname(__DIR__));

require SMSGRAM . '/bootstrap/app.php';

use App\Core\Database;

$db = Database::getInstance()->raw();

$db->query('CREATE TABLE IF NOT EXISTS `migrations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `migration` VARCHAR(191) NOT NULL,
    `ran_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$already = [];
$result = $db->query('SELECT migration FROM migrations');
while ($row = $result->fetch_assoc()) {
    $already[$row['migration']] = true;
}

$files = glob(__DIR__ . '/migrations/*.sql');
sort($files);

foreach ($files as $file) {
    $name = basename($file);

    if (isset($already[$name])) {
        echo "SKIP  {$name}\n";
        continue;
    }

    $sql = file_get_contents($file);

    if ($db->multi_query($sql)) {
        do {
            if ($res = $db->store_result()) {
                $res->free();
            }
        } while ($db->more_results() && $db->next_result());
    }

    if ($db->errno) {
        fwrite(STDERR, "XATO  {$name}: {$db->error}\n");
        exit(1);
    }

    $stmt = $db->prepare('INSERT INTO migrations (migration) VALUES (?)');
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $stmt->close();

    echo "OK    {$name}\n";
}

/*
|--------------------------------------------------------------------------
| Admin akkauntlari — foydalanuvchilardan butunlay ajratilgan
|--------------------------------------------------------------------------
| Agar `users` jadvalida role='admin' bo'lgan yozuvlar qolgan bo'lsa (eski
| tuzilma), ular `admins` jadvaliga ko'chiriladi va users'dagi admin
| belgisi olib tashlanadi — ikkala tizim orasida hech qanday bog'liqlik
| qolmasligi uchun.
*/
function unique_username(mysqli $db, string $base): string
{
    $base = preg_replace('/[^a-z0-9_]/', '', strtolower($base)) ?: 'admin';
    $username = $base;
    $suffix = 1;

    while (true) {
        $stmt = $db->prepare('SELECT id FROM admins WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            return $username;
        }
        $username = $base . $suffix++;
    }
}

$legacyAdmins = $db->query("SELECT id, name, email FROM users WHERE role = 'admin'");

if ($legacyAdmins && $legacyAdmins->num_rows > 0) {
    echo "\nEski admin foydalanuvchilar topildi, alohida `admins` jadvaliga ko'chirilmoqda:\n";

    while ($row = $legacyAdmins->fetch_assoc()) {
        $localPart = explode('@', $row['email'])[0];
        $username = unique_username($db, $localPart);
        $password = bin2hex(random_bytes(4));
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $insert = $db->prepare('INSERT INTO admins (username, name, password) VALUES (?, ?, ?)');
        $insert->bind_param('sss', $username, $row['name'], $hash);
        $insert->execute();
        $insert->close();

        $revert = $db->prepare("UPDATE users SET role = 'user' WHERE id = ?");
        $revert->bind_param('i', $row['id']);
        $revert->execute();
        $revert->close();

        echo "  - {$row['name']}: username=\"{$username}\", parol=\"{$password}\"\n";
    }
}

$adminCount = $db->query('SELECT COUNT(*) as c FROM admins')->fetch_assoc()['c'];

if ((int) $adminCount === 0) {
    $username = 'admin';
    $password = bin2hex(random_bytes(4));
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $db->prepare('INSERT INTO admins (username, name, password) VALUES (?, "SMSGram Admin", ?)');
    $stmt->bind_param('ss', $username, $hash);
    $stmt->execute();
    $stmt->close();

    echo "\nSTANDART ADMIN YARATILDI:\n";
    echo "  Login sahifasi: /admin/login\n";
    echo "  Username: {$username}\n";
    echo "  Parol:    {$password}\n";
    echo "  (Birinchi kirishdan so'ng parolni almashtiring)\n";
}

echo "\nMigratsiya yakunlandi.\n";
