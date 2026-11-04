<?php
$themeLabels = $labels ?? [
    'light' => __('theme.light'),
    'dark' => __('theme.dark'),
    'auto' => __('theme.auto'),
];
?>
<div class="inline-flex items-center gap-0.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 p-0.5">
    <button type="button" onclick="setTheme('light')" data-theme-btn="light" class="w-7 h-7 rounded-md flex items-center justify-center text-slate-400 dark:text-slate-500 transition-colors" title="<?= e($themeLabels['light']) ?>">
        <?= icon('sun', 'w-4 h-4') ?>
    </button>
    <button type="button" onclick="setTheme('dark')" data-theme-btn="dark" class="w-7 h-7 rounded-md flex items-center justify-center text-slate-400 dark:text-slate-500 transition-colors" title="<?= e($themeLabels['dark']) ?>">
        <?= icon('moon', 'w-4 h-4') ?>
    </button>
    <button type="button" onclick="setTheme('auto')" data-theme-btn="auto" class="w-7 h-7 rounded-md flex items-center justify-center text-slate-400 dark:text-slate-500 transition-colors" title="<?= e($themeLabels['auto']) ?>">
        <?= icon('desktop', 'w-4 h-4') ?>
    </button>
</div>
