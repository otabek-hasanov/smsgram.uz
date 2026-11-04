<div class="grid lg:grid-cols-3 gap-6">
    <div class="min-w-0 lg:col-span-1 space-y-6">
        <div class="card p-6">
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1"><?= e(__('balance.current_balance')) ?></p>
            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mb-6"><?= money((float) $user['balance']) ?></p>

            <form action="<?= route('/balance/topup') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="label" for="amount"><?= e(__('balance.topup_amount')) ?></label>
                    <input class="input" type="number" min="<?= (int) $minAmount ?>" max="<?= (int) $maxAmount ?>" step="1000" name="amount" id="amount" placeholder="50000" required>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1"><?= money($minAmount) ?> — <?= money($maxAmount) ?></p>
                </div>
                <button type="submit" class="btn-primary w-full inline-flex items-center justify-center gap-2">
                    <?= icon('card', 'w-4 h-4') ?> <?= e(__('balance.topup_btn')) ?>
                </button>
                <p class="text-xs text-slate-400 dark:text-slate-500 text-center"><?= e(__('balance.provider_note')) ?></p>
            </form>
        </div>

        <?php if (!empty($pendingPayments)): ?>
            <div class="card p-6">
                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-4"><?= e(__('balance.pending_title')) ?></p>
                <div class="space-y-3">
                    <?php foreach ($pendingPayments as $p): ?>
                        <div class="flex items-center justify-between rounded-lg border border-amber-100 dark:border-amber-500/20 bg-amber-50 dark:bg-amber-500/10 px-3 py-2.5">
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100"><?= money((float) $p['amount']) ?></p>
                                <p class="text-xs text-slate-400 dark:text-slate-500"><?= e($p['created_at']) ?></p>
                            </div>
                            <?php if (!empty($p['payment_url'])): ?>
                                <a href="<?= e($p['payment_url']) ?>" class="text-xs font-medium text-brand-600 dark:text-brand-400 hover:underline"><?= e(__('balance.continue_payment')) ?></a>
                            <?php else: ?>
                                <span class="badge-yellow"><?= e(__('balance.status_pending')) ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="min-w-0 lg:col-span-2">
        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 font-semibold"><?= e(__('balance.history_title')) ?></div>
            <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[640px]">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="px-6 py-3 font-medium"><?= e(__('balance.type')) ?></th>
                        <th class="px-6 py-3 font-medium"><?= e(__('balance.amount')) ?></th>
                        <th class="px-6 py-3 font-medium"><?= e(__('balance.remaining')) ?></th>
                        <th class="px-6 py-3 font-medium"><?= e(__('balance.description')) ?></th>
                        <th class="px-6 py-3 font-medium"><?= e(__('common.date')) ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($pagination['items'])): ?>
                        <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500"><?= e(__('balance.empty')) ?></td></tr>
                    <?php endif; ?>
                    <?php foreach ($pagination['items'] as $tx): ?>
                        <tr>
                            <td class="px-6 py-3">
                                <span class="<?= $tx['type'] === 'topup' ? 'badge-green' : ($tx['type'] === 'refund' ? 'badge-yellow' : 'badge-red') ?>">
                                    <?= e(__('balance.type_' . $tx['type'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-3 font-medium <?= $tx['type'] === 'charge' ? 'text-red-600 dark:text-red-400' : 'text-brand-600 dark:text-brand-400' ?>">
                                <?= $tx['type'] === 'charge' ? '-' : '+' ?><?= money((float) $tx['amount']) ?>
                            </td>
                            <td class="px-6 py-3 text-slate-500 dark:text-slate-400"><?= money((float) $tx['balance_after']) ?></td>
                            <td class="px-6 py-3 text-slate-500 dark:text-slate-400"><?= e($tx['description'] ?? '') ?></td>
                            <td class="px-6 py-3 text-slate-500 dark:text-slate-400"><?= e($tx['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
