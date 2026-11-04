<div class="grid lg:grid-cols-3 gap-6">
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-brand-100 dark:bg-brand-500/10 text-brand-700 dark:text-brand-400 flex items-center justify-center font-bold text-lg">
                <?= e(mb_substr($user['name'], 0, 1)) ?>
            </div>
            <div>
                <p class="font-semibold text-slate-900 dark:text-slate-100"><?= e($user['name']) ?></p>
                <p class="text-sm text-slate-500 dark:text-slate-400"><?= e($user['email']) ?></p>
            </div>
        </div>
        <dl class="text-sm space-y-2 mb-6">
            <div class="flex justify-between"><dt class="text-slate-500 dark:text-slate-400"><?= e('Telefon') ?></dt><dd><?= e($user['phone'] ?? '—') ?></dd></div>
            <div class="flex justify-between"><dt class="text-slate-500 dark:text-slate-400"><?= e('Balans') ?></dt><dd class="font-medium"><?= money((float) $user['balance']) ?></dd></div>
            <div class="flex justify-between"><dt class="text-slate-500 dark:text-slate-400"><?= e('Ro\'yxatdan o\'tgan') ?></dt><dd><?= e($user['created_at']) ?></dd></div>
        </dl>

        <form action="/admin/users/<?= $user['id'] ?>/toggle-status" method="POST">
            <?= csrf_field() ?>
            <button type="submit" class="btn-<?= $user['status'] === 'active' ? 'danger' : 'primary' ?> w-full">
                <?= $user['status'] === 'active' ? e('Bloklash') : e('Blokdan chiqarish') ?>
            </button>
        </form>
    </div>

    <div class="card p-6">
        <h2 class="font-semibold mb-4"><?= e('Balansni boshqarish') ?></h2>
        <form action="/admin/users/<?= $user['id'] ?>/balance" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="label" for="amount"><?= e('Summa') ?></label>
                <input class="input" type="number" min="1" step="0.01" name="amount" id="amount" required>
            </div>
            <div>
                <label class="label" for="type"><?= e('Amallar') ?></label>
                <select class="input" name="type" id="type">
                    <option value="topup"><?= e('To\'ldirish') ?> (+)</option>
                    <option value="deduct"><?= e('Yechish') ?> (-)</option>
                </select>
            </div>
            <button type="submit" class="btn-primary w-full"><?= e('Tasdiqlash') ?></button>
        </form>
    </div>

    <div class="card overflow-hidden lg:col-span-1">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 font-semibold"><?= e('Oxirgi tranzaksiyalar') ?></div>
        <div class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php if (empty($transactions['items'])): ?>
                <p class="px-6 py-6 text-sm text-slate-400 dark:text-slate-500"><?= e('Hali tranzaksiyalar yo\'q') ?></p>
            <?php endif; ?>
            <?php foreach ($transactions['items'] as $tx): ?>
                <div class="px-6 py-3 flex justify-between items-center text-sm">
                    <div>
                        <span class="<?= $tx['type'] === 'topup' ? 'badge-green' : ($tx['type'] === 'refund' ? 'badge-yellow' : 'badge-red') ?>">
                            <?= e(match ($tx['type']) { 'topup' => "To'ldirish", 'refund' => 'Qaytarish', default => 'Yechish' }) ?>
                        </span>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1"><?= e($tx['created_at']) ?></p>
                    </div>
                    <span class="font-medium <?= $tx['type'] === 'charge' ? 'text-red-600 dark:text-red-400' : 'text-brand-600 dark:text-brand-400' ?>">
                        <?= $tx['type'] === 'charge' ? '-' : '+' ?><?= money((float) $tx['amount']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
