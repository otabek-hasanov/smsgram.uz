# SMSGram

O'zbekiston uchun SMS shlyuz platformasi — foydalanuvchilar shablon asosida SMS yuboradi, balansni checkout.uz orqali to'ldiradi, admin panel orqali shablonlar va foydalanuvchilar boshqariladi.

Laravel uslubidagi tuzilmaga ega **custom PHP 8.5 framework** ustida qurilgan — tashqi full-stack freymvork ishlatilmagan.

## Texnologiyalar

- **PHP 8.5**, faqat `mysqli` (PDO ishlatilmagan)
- [bramus/router](https://github.com/bramus/router) — marshrutlash
- [doctrine/cache](https://github.com/doctrine/cache) + [predis/predis](https://github.com/predis/predis) — Redis orqali keshlash
- [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) — tashqi API so'rovlari
- [giggsey/libphonenumber-for-php](https://github.com/giggsey/libphonenumber-for-php) — O'zbekiston raqamlarini tekshirish
- [Tailwind CSS](https://tailwindcss.com/) — frontend
- MySQL 8+, Redis

## Arxitektura haqida muhim izohlar

- **`.env` fayli ishlatilmaydi.** Barcha konfiguratsiya `app/Config/AppConfig.php` ichida to'g'ridan-to'g'ri PHP massivi sifatida saqlanadi. `.env.example` faqat qaysi qiymatlarni to'ldirish kerakligini ko'rsatuvchi **hujjat** sifatida berilgan — uni avtomatik o'qiydigan kod yo'q.
- **Butun loyihada bitta `require`** bor (`index.php` → `bootstrap/app.php` → Composer autoload). Qolgan hamma narsa PSR-4 namespace orqali ishlaydi.
- **Admin panel** (`/admin`) oddiy foydalanuvchilardan butunlay mustaqil: alohida `admins` jadvali, alohida sessiya, alohida login sahifasi, tilsiz (faqat o'zbek tilida).
- **Foydalanuvchi paneli** uch tilli: `/uz/...`, `/ru/...`, `/en/...` URL prefikslari orqali (sessiyaga bog'liq emas).
- SMS yuborish faqat **O'zbekiston mobil raqamlariga** ruxsat etilgan (`libphonenumber` orqali tekshiriladi).

## Loyiha tuzilmasi

```
app/
  Config/        — AppConfig.php (barcha sozlamalar shu yerda)
  Core/          — Router bootstrap, Database (mysqli wrapper), Auth, Cache, View, Session, helpers.php
  Http/
    Controllers/
      Admin/     — Admin panel kontrollerlari
      Auth/      — Login/Register
      User/      — Foydalanuvchi paneli (SMS, shablonlar, balans, profil)
      Api/       — Tashqi REST API (Bearer API-key orqali)
  Models/        — User, SmsTemplate, SmsMessage, Transaction, Payment, ApiKey, Setting...
  Services/      — SmsProviderService (TextUp), CheckoutService, PhoneValidator, PaymentReconciler
  Lang/          — Uz.php, Ru.php, En.php (tarjimalar)
  Routes/        — WebRoutes.php, ApiRoutes.php
resources/
  views/         — PHP shablonlar (Blade emas, oddiy PHP + layout tizimi)
  css/           — Tailwind manba fayli
database/
  migrations/    — Ketma-ket SQL migratsiyalar (manba hujjat)
  schema.sql     — To'liq baza tuzilmasining bitta faylga yig'ilgan snapshoti
  migrate.php    — Migratsiya runner (CLI)
bootstrap/
  app.php        — Composer autoload, router va konteyner ishga tushirish
index.php        — Yagona kirish nuqtasi (front controller)
```

## O'rnatish

### 1. Talablar

- PHP 8.5+ (`mysqli`, `gd` kengaytmalari bilan)
- MySQL 8+ (yoki mos keladigan MariaDB)
- Redis server
- Composer
- Node.js + npm (faqat Tailwind CSS build qilish uchun)

### 2. Repozitoriyni klonlash va bog'liqliklarni o'rnatish

```bash
git clone https://github.com/<username>/smsgram.uz.git
cd smsgram.uz
composer install
npm install
npm run build
```

### 3. MySQL bazasini yaratish

```sql
CREATE DATABASE smsgram CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'smsgram'@'localhost' IDENTIFIED BY 'kuchli-parolingiz';
GRANT ALL PRIVILEGES ON smsgram.* TO 'smsgram'@'localhost';
FLUSH PRIVILEGES;
```

### 4. Baza tuzilmasini o'rnatish

**Tavsiya etiladigan usul** — migratsiya runnerini ishga tushiring (bazani yaratadi va standart admin hisobini avtomatik hosil qiladi):

```bash
php database/migrate.php
```

Bajarilgach, terminalda standart admin login/parolini ko'rasiz (`/admin/login` orqali kirish uchun).

**Muqobil usul** — tayyor to'liq strukturani to'g'ridan-to'g'ri import qilish (bu holda admin hisobini qo'lda yaratishingiz kerak bo'ladi):

```bash
mysql -u smsgram -p smsgram < database/schema.sql
```

### 5. Konfiguratsiya

`.env.example` faylidagi qiymatlarni o'qib, ularni **qo'lda** `app/Config/AppConfig.php` fayliga kiriting:

- `database` — MySQL ulanish ma'lumotlari
- `redis` — Redis ulanish ma'lumotlari
- `sms_provider` — [TextUp](https://textup.uz) hisobingiz ma'lumotlari (SMS yuborish uchun)
- `checkout` — [checkout.uz](https://checkout.uz) hisobingiz API kaliti (balansni avtomatik to'ldirish uchun)

### 6. Veb-serverni sozlash

Dokument ildizi (`DocumentRoot`) loyiha papkasining o'ziga (`index.php` joylashgan joyga) ko'rsatilishi kerak.

**Apache**: `.htaccess` fayli allaqachon tayyor (`mod_rewrite`, `mod_deflate`, `mod_headers` kerak).

**Nginx** uchun namuna:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.5-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
}

location ~ ^/(app|database|bootstrap|storage|vendor)/ {
    deny all;
}
```

### 7. Papka ruxsatlari

```bash
chmod -R 775 storage
```

## Ishga tushirish (lokal rivojlantirish)

```bash
php -S localhost:8000 index.php
npm run watch   # Tailwind CSS'ni kuzatish rejimida build qilish
```

Brauzerda `http://localhost:8000` manzilini oching.

## Papka/fayl ruxsatlari va xavfsizlik eslatmalari

- `storage/`, `vendor/`, `app/`, `database/`, `bootstrap/` papkalariga tashqaridan to'g'ridan-to'g'ri kirish `.htaccess` orqali bloklangan — buni nginx konfiguratsiyasida ham takrorlang.
- `app/Config/AppConfig.php` ichidagi maxfiy qiymatlarni (parollar, API kalitlar) hech qachon versiya nazoratiga (git) qo'shmang. Ushbu repozitoriyda bu fayl faqat `CHANGE_ME` placeholder'lari bilan beriladi.
- Admin panel (`/admin`) alohida autentifikatsiya tizimiga ega — uni oddiy foydalanuvchi autentifikatsiyasi bilan hech qachon aralashtirmang.

## Litsenziya

Mulkiy (proprietary) — `composer.json` ga qarang.
