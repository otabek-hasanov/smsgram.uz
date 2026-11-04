<?php

$navItems = \App\Core\NavMenu::user();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$user = auth_user();
?>
<!DOCTYPE html>
<html lang="<?= e(current_locale()) ?>">
<head>
    <meta charset="UTF-8">
    <?php view('partials.theme-init'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? __('common.app_name')) ?> — <?= e(__('common.app_name')) ?></title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <meta name="theme-color" content="#2563eb">
    <link rel="stylesheet" href="<?= asset_url('build/app.css') ?>">
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased">

<div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 z-30 hidden lg:hidden"></div>

<div class="flex min-h-screen">

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full transition-transform duration-200 ease-out lg:translate-x-0 lg:static lg:z-auto flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-700">
        <div class="h-16 flex items-center justify-between gap-2 px-6 font-bold text-lg border-b border-slate-100 dark:border-slate-800 shrink-0">
            <a href="<?= route() ?>" class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 text-white flex items-center justify-center text-sm"><?= logo_mark('w-4 h-4') ?></span>
                <?= e(__('common.app_name')) ?>
            </a>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300"><?= icon('close', 'w-5 h-5') ?></button>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= e($item['href']) ?>"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors <?= $currentPath === $item['href'] ? 'bg-brand-50 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                    <?= icon($item['icon'], 'w-5 h-5 shrink-0') ?>
                    <span><?= e($item['label']) ?></span>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="p-4 border-t border-slate-100 dark:border-slate-800 shrink-0">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-full bg-brand-100 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 flex items-center justify-center font-semibold text-sm shrink-0">
                    <?= e(mb_substr($user['name'] ?? '?', 0, 1)) ?>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate"><?= e($user['name'] ?? '') ?></p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate"><?= e($user['email'] ?? '') ?></p>
                </div>
            </div>
            <a href="<?= route('/logout') ?>" class="flex items-center justify-center gap-2 btn-secondary w-full text-sm">
                <?= icon('logout', 'w-4 h-4') ?> <?= e(__('nav.logout')) ?>
            </a>
        </div>
    </aside>

    <div class="flex-1 min-w-0 flex flex-col">
        <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-20">
            <div class="flex items-center gap-3 min-w-0">
                <button onclick="toggleSidebar()" class="lg:hidden text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100"><?= icon('menu', 'w-6 h-6') ?></button>
                <h1 class="font-semibold text-lg text-slate-900 dark:text-slate-100 truncate"><?= e($title ?? '') ?></h1>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <span class="hidden sm:inline-flex badge-green"><?= e(__('dashboard.current_balance')) ?>: <?= money((float) ($user['balance'] ?? 0)) ?></span>
                <?php view('partials.language-switcher'); ?>
                <?php view('partials.profile-menu', ['user' => $user]); ?>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-8">
            <?php if ($error = flash('error')): ?>
                <div class="mb-5 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-100 dark:border-red-500/20 text-red-700 dark:text-red-400 text-sm px-4 py-3"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success = flash('success')): ?>
                <div class="mb-5 rounded-xl bg-brand-50 dark:bg-brand-500/10 border border-brand-100 dark:border-brand-500/20 text-brand-700 dark:text-brand-400 text-sm px-4 py-3"><?= e($success) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('sidebar-backdrop').classList.toggle('hidden');
}
</script>

</body>
</html>
