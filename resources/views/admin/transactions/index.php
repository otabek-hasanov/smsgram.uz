<div class="card overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-left">
            <tr>
                <th class="px-6 py-3 font-medium">ID</th>
                <th class="px-6 py-3 font-medium"><?= e('Foydalanuvchilar') ?> ID</th>
                <th class="px-6 py-3 font-medium"><?= e('Turi') ?></th>
                <th class="px-6 py-3 font-medium"><?= e('Summa') ?></th>
                <th class="px-6 py-3 font-medium"><?= e('Qoldiq') ?></th>
                <th class="px-6 py-3 font-medium"><?= e('Sana') ?></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php foreach ($pagination['items'] as $tx): ?>
                <tr>
                    <td class="px-6 py-3 text-slate-400 dark:text-slate-500">#<?= $tx['id'] ?></td>
                    <td class="px-6 py-3">
                        <a href="/admin/users/<?= $tx['user_id'] ?>" class="text-brand-600 dark:text-brand-400 hover:underline">#<?= $tx['user_id'] ?></a>
                    </td>
                    <td class="px-6 py-3">
                        <span class="<?= $tx['type'] === 'topup' ? 'badge-green' : ($tx['type'] === 'refund' ? 'badge-yellow' : 'badge-red') ?>"><?= e(match ($tx['type']) { 'topup' => "To'ldirish", 'refund' => 'Qaytarish', default => 'Yechish' }) ?></span>
                    </td>
                    <td class="px-6 py-3 font-medium <?= $tx['type'] === 'charge' ? 'text-red-600 dark:text-red-400' : 'text-brand-600 dark:text-brand-400' ?>">
                        <?= $tx['type'] === 'charge' ? '-' : '+' ?><?= money((float) $tx['amount']) ?>
                    </td>
                    <td class="px-6 py-3 text-slate-500 dark:text-slate-400"><?= money((float) $tx['balance_after']) ?></td>
                    <td class="px-6 py-3 text-slate-500 dark:text-slate-400"><?= e($tx['created_at']) ?></td>
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
