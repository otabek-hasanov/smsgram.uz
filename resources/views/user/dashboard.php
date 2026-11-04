<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="card p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1"><?= e(__('dashboard.current_balance')) ?></p>
            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= money((float) $user['balance']) ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0"><?= icon('card', 'w-5 h-5') ?></div>
    </div>
    <div class="card p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1"><?= e(__('dashboard.total_sent')) ?></p>
            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= (int) $stats['total'] ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center justify-center shrink-0"><?= icon('send', 'w-5 h-5') ?></div>
    </div>
    <div class="card p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1"><?= e(__('dashboard.delivered')) ?></p>
            <p class="text-2xl font-bold text-brand-600 dark:text-brand-400"><?= (int) $stats['delivered'] ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0"><?= icon('check-circle', 'w-5 h-5') ?></div>
    </div>
    <div class="card p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1"><?= e(__('dashboard.failed')) ?></p>
            <p class="text-2xl font-bold text-red-500 dark:text-red-400"><?= (int) $stats['failed'] ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 dark:text-red-400 flex items-center justify-center shrink-0"><?= icon('x-circle', 'w-5 h-5') ?></div>
    </div>
</div>

<div class="flex flex-wrap gap-3 mb-8">
    <a href="<?= route('/sms/send') ?>" class="btn-primary inline-flex items-center gap-2">
        <?= icon('plus', 'w-4 h-4') ?> <?= e(__('dashboard.new_sms')) ?>
    </a>
    <a href="<?= route('/balance') ?>" class="btn-secondary inline-flex items-center gap-2">
        <?= icon('card', 'w-4 h-4') ?> <?= e(__('dashboard.topup')) ?>
    </a>
    <a href="<?= route('/templates/create') ?>" class="btn-secondary inline-flex items-center gap-2">
        <?= icon('template', 'w-4 h-4') ?> <?= e(__('templates.create_new')) ?>
    </a>
</div>

<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 font-semibold flex items-center justify-between">
        <span><?= e(__('dashboard.recent_messages')) ?></span>
        <a href="<?= route('/sms/history') ?>" class="text-sm text-brand-600 dark:text-brand-400 hover:underline font-normal"><?= e(__('nav.history')) ?></a>
    </div>
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-left">
            <tr>
                <th class="px-6 py-3 font-medium"><?= e(__('dashboard.recipients')) ?></th>
                <th class="px-6 py-3 font-medium"><?= e(__('dashboard.message')) ?></th>
                <th class="px-6 py-3 font-medium"><?= e(__('common.status')) ?></th>
                <th class="px-6 py-3 font-medium"><?= e(__('dashboard.cost')) ?></th>
                <th class="px-6 py-3 font-medium"><?= e(__('common.date')) ?></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php if (empty($recent)): ?>
                <tr><td colspan="5" class="px-6 py-14 text-center text-slate-400 dark:text-slate-500">
                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-3"><?= icon('send', 'w-5 h-5') ?></div>
                    <?= e(__('dashboard.empty')) ?>
                </td></tr>
            <?php endif; ?>
            <?php foreach ($recent as $sms): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                    <td class="px-6 py-3 font-mono text-slate-600 dark:text-slate-400"><?= (int) $sms['recipients_count'] ?></td>
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
