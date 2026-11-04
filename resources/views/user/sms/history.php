<div class="card overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-left">
            <tr>
                <th class="px-6 py-3 font-medium"><?= e(__('dashboard.recipients')) ?></th>
                <th class="px-6 py-3 font-medium"><?= e(__('sms.template_col')) ?></th>
                <th class="px-6 py-3 font-medium"><?= e(__('dashboard.message')) ?></th>
                <th class="px-6 py-3 font-medium"><?= e(__('common.status')) ?></th>
                <th class="px-6 py-3 font-medium"><?= e(__('dashboard.cost')) ?></th>
                <th class="px-6 py-3 font-medium"><?= e(__('common.date')) ?></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php if (empty($pagination['items'])): ?>
                <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500"><?= e(__('dashboard.empty')) ?></td></tr>
            <?php endif; ?>
            <?php foreach ($pagination['items'] as $sms): ?>
                <tr>
                    <td class="px-6 py-3 font-mono text-slate-600 dark:text-slate-400"><?= (int) $sms['recipients_count'] ?></td>
                    <td class="px-6 py-3 text-slate-500 dark:text-slate-400"><?= e($sms['template_name'] ?? '—') ?></td>
                    <td class="px-6 py-3 text-slate-600 dark:text-slate-400 max-w-xs truncate"><?= e($sms['message']) ?></td>
                    <td class="px-6 py-3"><span class="<?= sms_status_badge($sms['status']) ?>"><?= e($sms['status']) ?></span></td>
                    <td class="px-6 py-3"><?= money((float) $sms['cost']) ?></td>
                    <td class="px-6 py-3 text-slate-500 dark:text-slate-400"><?= e($sms['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?php if ($pagination['last_page'] > 1): ?>
<div class="flex justify-center gap-2 mt-6">
    <?php for ($p = 1; $p <= $pagination['last_page']; $p++): ?>
        <a href="?page=<?= $p ?>"
           class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-medium <?= $p === $pagination['page'] ? 'bg-brand-600 text-white' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700' ?>">
            <?= $p ?>
        </a>
    <?php endfor; ?>
</div>
<?php endif; ?>
