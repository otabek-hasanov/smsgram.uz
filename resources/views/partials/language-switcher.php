<?php
$locales = \App\Core\NavMenu::locales();
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
// Joriy yo'ldan til prefiksini olib tashlaymiz, qolgan qismi saqlanadi
// (masalan /ru/sms/send -> /sms/send), shunda til almashtirilganda
// foydalanuvchi xuddi shu sahifada qoladi.
$pathWithoutLocale = preg_replace('#^/(uz|ru|en)#', '', $currentPath);
?>
<details class="relative">
    <summary class="list-none flex items-center gap-1.5 cursor-pointer rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100 [&::-webkit-details-marker]:hidden">
        <?= icon('globe', 'w-4 h-4') ?>
        <span class="uppercase"><?= e(current_locale()) ?></span>
        <?= icon('chevron-down', 'w-3.5 h-3.5') ?>
    </summary>
    <div class="absolute right-0 mt-2 w-36 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg py-1.5 z-50">
        <?php foreach ($locales as $code => $label): ?>
            <a href="/<?= e($code) ?><?= e($pathWithoutLocale) ?>"
               class="flex items-center justify-between px-3.5 py-2 text-sm <?= current_locale() === $code ? 'text-brand-600 dark:text-brand-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' ?>">
                <?= e($label) ?>
                <?php if (current_locale() === $code): ?><?= icon('check-circle', 'w-4 h-4') ?><?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
</details>
