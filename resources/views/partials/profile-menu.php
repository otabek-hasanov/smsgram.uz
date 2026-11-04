<details class="relative">
    <summary class="list-none w-9 h-9 rounded-full bg-brand-100 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 flex items-center justify-center font-semibold text-sm shrink-0 cursor-pointer [&::-webkit-details-marker]:hidden">
        <?= e(mb_substr($user['name'] ?? '?', 0, 1)) ?>
    </summary>
    <div class="absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-lg py-2 z-50">
        <div class="px-4 py-2 mb-1 border-b border-slate-100 dark:border-slate-800">
            <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate"><?= e($user['name'] ?? '') ?></p>
            <p class="text-xs text-slate-500 dark:text-slate-400 truncate"><?= e($user['email'] ?? '') ?></p>
        </div>

        <a href="<?= route('/profile') ?>" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800">
            <?= icon('user', 'w-4 h-4') ?> <?= e(__('nav.profile')) ?>
        </a>

        <div class="flex items-center justify-between gap-2 px-4 py-2">
            <span class="text-sm text-slate-600 dark:text-slate-400"><?= e(__('theme.label')) ?></span>
            <?php view('partials.theme-switcher'); ?>
        </div>

        <div class="border-t border-slate-100 dark:border-slate-800 my-1"></div>

        <a href="<?= route('/logout') ?>" class="flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10">
            <?= icon('logout', 'w-4 h-4') ?> <?= e(__('nav.logout')) ?>
        </a>
    </div>
</details>
