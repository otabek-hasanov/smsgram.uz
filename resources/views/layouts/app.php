<?php
$metaDescription = $description ?? __('seo.home_desc', ['price' => (int) \App\Models\Setting::get('price_per_sms', 200)]);
$metaKeywords = $keywords ?? __('seo.home_keywords');
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$pathWithoutLocale = preg_replace('#^/(uz|ru|en)#', '', $currentPath);
$canonical = 'https://smsgram.uz' . strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
$ogLocaleMap = ['uz' => 'uz_UZ', 'ru' => 'ru_RU', 'en' => 'en_US'];
?>
<!DOCTYPE html>
<html lang="<?= e(current_locale()) ?>">
<head>
    <meta charset="UTF-8">
    <?php view('partials.theme-init'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? __('common.app_name')) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <meta name="keywords" content="<?= e($metaKeywords) ?>">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <meta name="author" content="SMSGram">
    <link rel="canonical" href="<?= e($canonical) ?>">

    <!-- Til variantlari (hreflang) — Google va boshqa qidiruv tizimlariga uz/ru/en versiyalarni bildiradi -->
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
    <meta property="og:site_name" content="<?= e(__('common.app_name')) ?>">
    <meta property="og:locale" content="<?= e($ogLocaleMap[current_locale()] ?? 'uz_UZ') ?>">
    <meta property="og:title" content="<?= e($title ?? __('common.app_name')) ?>">
    <meta property="og:description" content="<?= e($metaDescription) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="https://smsgram.uz/og-image.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($title ?? __('common.app_name')) ?>">
    <meta name="twitter:description" content="<?= e($metaDescription) ?>">
    <meta name="twitter:image" content="https://smsgram.uz/og-image.png">

    <link rel="stylesheet" href="<?= asset_url('build/app.css') ?>">
</head>
<body class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 antialiased">

    <header class="sticky top-0 z-40 border-b border-slate-100 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="<?= route() ?>" class="flex items-center gap-2 font-bold text-lg text-slate-900 dark:text-slate-100">
                <span class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center text-sm shadow-md shadow-brand-600/20"><?= logo_mark('w-4 h-4') ?></span>
                <?= e(__('common.app_name')) ?>
            </a>
            <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-slate-500 dark:text-slate-400">
                <a href="<?= route() ?>#features" class="hover:text-slate-900 dark:hover:text-slate-100 transition-colors"><?= e(__('nav.features')) ?></a>
                <a href="<?= route() ?>#how" class="hover:text-slate-900 dark:hover:text-slate-100 transition-colors"><?= e(__('home.how_title')) ?></a>
                <a href="<?= route() ?>#pricing" class="hover:text-slate-900 dark:hover:text-slate-100 transition-colors"><?= e(__('nav.pricing')) ?></a>
                <a href="<?= route() ?>#faq" class="hover:text-slate-900 dark:hover:text-slate-100 transition-colors"><?= e(__('home.faq_title')) ?></a>
                <a href="<?= route('/docs') ?>" class="hover:text-slate-900 dark:hover:text-slate-100 transition-colors"><?= e(__('nav.api')) ?></a>
            </nav>
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="hidden sm:block"><?php view('partials.theme-switcher'); ?></div>
                <div class="hidden sm:block"><?php view('partials.language-switcher'); ?></div>
                <?php if (auth_check()): ?>
                    <a href="<?= route('/dashboard') ?>" class="btn-primary"><?= e(__('nav.go_to_panel')) ?></a>
                <?php else: ?>
                    <a href="<?= route('/login') ?>" class="hidden sm:inline-flex btn-secondary"><?= e(__('nav.login')) ?></a>
                    <a href="<?= route('/register') ?>" class="btn-primary"><?= e(__('nav.register')) ?></a>
                <?php endif; ?>
                <details class="md:hidden relative">
                    <summary class="list-none w-9 h-9 rounded-lg flex items-center justify-center text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer [&::-webkit-details-marker]:hidden">
                        <?= icon('menu', 'w-5 h-5') ?>
                    </summary>
                    <div class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg py-2 z-50">
                        <a href="<?= route() ?>#features" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800"><?= e(__('nav.features')) ?></a>
                        <a href="<?= route() ?>#how" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800"><?= e(__('home.how_title')) ?></a>
                        <a href="<?= route() ?>#pricing" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800"><?= e(__('nav.pricing')) ?></a>
                        <a href="<?= route() ?>#faq" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800"><?= e(__('home.faq_title')) ?></a>
                        <a href="<?= route('/docs') ?>" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800"><?= e(__('nav.api')) ?></a>
                        <div class="border-t border-slate-100 dark:border-slate-800 my-2"></div>
                        <div class="px-4 py-1 flex items-center justify-between gap-2">
                            <?php view('partials.language-switcher'); ?>
                            <?php view('partials.theme-switcher'); ?>
                        </div>
                        <?php if (!auth_check()): ?>
                            <a href="<?= route('/login') ?>" class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800"><?= e(__('nav.login')) ?></a>
                        <?php endif; ?>
                    </div>
                </details>
            </div>
        </div>
    </header>

    <main><?= $content ?></main>

    <footer class="border-t border-slate-100 dark:border-slate-800 mt-24 bg-slate-50 dark:bg-slate-800/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-10">
                <div>
                    <div class="flex items-center gap-2 font-bold text-slate-900 dark:text-slate-100 mb-3">
                        <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center text-xs"><?= logo_mark('w-4 h-4') ?></span>
                        <?= e(__('common.app_name')) ?>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed"><?= e(__('home.hero_desc')) ?></p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3"><?= e(__('nav.features')) ?></h4>
                    <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                        <li><a href="<?= route() ?>#features" class="hover:text-brand-600"><?= e(__('home.feature1_title')) ?></a></li>
                        <li><a href="<?= route('/docs') ?>" class="hover:text-brand-600"><?= e(__('home.feature2_title')) ?></a></li>
                        <li><a href="<?= route() ?>#features" class="hover:text-brand-600"><?= e(__('home.feature3_title')) ?></a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3"><?= e(__('nav.pricing')) ?></h4>
                    <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                        <li><a href="<?= route() ?>#pricing" class="hover:text-brand-600"><?= e(__('home.pricing_title')) ?></a></li>
                        <li><a href="<?= route() ?>#faq" class="hover:text-brand-600"><?= e(__('home.faq_title')) ?></a></li>
                        <li><a href="<?= route('/register') ?>" class="hover:text-brand-600"><?= e(__('home.pricing_cta')) ?></a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3"><?= e(__('home.contact_title')) ?></h4>
                    <ul class="space-y-2 text-sm text-slate-500 dark:text-slate-400">
                        <li>support@smsgram.uz</li>
                        <li>+998 71 200 00 00</li>
                        <li><?= e(__('home.contact_address')) ?></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-200 dark:border-slate-700 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500 dark:text-slate-400">
                <p>&copy; <?= date('Y') ?> <?= e(__('common.app_name')) ?>. smsgram.uz</p>
                <div class="flex items-center gap-2"><?php view('partials.language-switcher'); ?></div>
            </div>
        </div>
    </footer>

</body>
</html>
