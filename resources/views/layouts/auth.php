<?php
$metaDescription = $description ?? __('seo.home_desc', ['price' => (int) \App\Models\Setting::get('price_per_sms', 200)]);
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$pathWithoutLocale = preg_replace('#^/(uz|ru|en)#', '', $currentPath);
$canonical = 'https://smsgram.uz' . strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
?>
<!DOCTYPE html>
<html lang="<?= e(current_locale()) ?>">
<head>
    <meta charset="UTF-8">
    <?php view('partials.theme-init'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? __('common.app_name')) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <link rel="alternate" hreflang="uz" href="https://smsgram.uz/uz<?= e($pathWithoutLocale) ?>">
    <link rel="alternate" hreflang="ru" href="https://smsgram.uz/ru<?= e($pathWithoutLocale) ?>">
    <link rel="alternate" hreflang="en" href="https://smsgram.uz/en<?= e($pathWithoutLocale) ?>">
    <link rel="alternate" hreflang="x-default" href="https://smsgram.uz/uz<?= e($pathWithoutLocale) ?>">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="/icon-32.png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#2563eb">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($title ?? __('common.app_name')) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:image" content="https://smsgram.uz/og-image.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="https://smsgram.uz/og-image.png">
    <link rel="stylesheet" href="<?= asset_url('build/app.css') ?>">
</head>
<body class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 antialiased">

<div class="min-h-screen flex">

    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-600 to-brand-800 flex-col justify-between p-12">
        <div class="pointer-events-none absolute inset-0 opacity-25" style="background-image: radial-gradient(circle at 80% 10%, white 0, transparent 35%), radial-gradient(circle at 10% 90%, white 0, transparent 30%);"></div>

        <a href="<?= route() ?>" class="relative flex items-center gap-2 font-bold text-xl text-white">
            <span class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center text-sm"><?= logo_mark('w-4 h-4') ?></span>
            <?= e(__('common.app_name')) ?>
        </a>

        <div class="relative">
            <h2 class="text-3xl font-bold text-white leading-tight mb-4"><?= e(__('home.hero_title_1')) ?><br><?= e(__('home.hero_title_2')) ?></h2>
            <p class="text-brand-50/90 max-w-sm leading-relaxed"><?= e(__('home.hero_desc')) ?></p>
        </div>

        <div class="relative flex items-center gap-6 text-brand-50/80 text-sm">
            <div class="flex items-center gap-2"><?= icon('bolt', 'w-4 h-4') ?> <?= e(__('home.feature1_title')) ?></div>
            <div class="flex items-center gap-2"><?= icon('shield', 'w-4 h-4') ?> <?= e(__('home.feature3_title')) ?></div>
        </div>
    </div>

    <div class="flex-1 flex flex-col">
        <div class="flex justify-between items-center p-6">
            <a href="<?= route() ?>" class="lg:hidden flex items-center gap-2 font-bold text-slate-900 dark:text-slate-100">
                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center text-sm"><?= logo_mark('w-4 h-4') ?></span>
                <?= e(__('common.app_name')) ?>
            </a>
            <div class="ml-auto flex items-center gap-2">
                <?php view('partials.theme-switcher'); ?>
                <?php view('partials.language-switcher'); ?>
            </div>
        </div>

        <div class="flex-1 flex items-center justify-center px-6 pb-12">
            <div class="w-full max-w-sm">
                <?php if ($error = flash('error')): ?>
                    <div class="mb-5 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-700 dark:text-red-400 text-sm px-4 py-3"><?= e($error) ?></div>
                <?php endif; ?>
                <?php if ($success = flash('success')): ?>
                    <div class="mb-5 rounded-xl bg-brand-50 dark:bg-brand-500/10 border border-brand-100 dark:border-brand-500/20 text-brand-700 dark:text-brand-400 text-sm px-4 py-3"><?= e($success) ?></div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>
    </div>

</div>

</body>
</html>
