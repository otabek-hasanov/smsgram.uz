<div class="card overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-left">
            <tr>
                <th class="px-6 py-3 font-medium"><?= e('To\'liq ism') ?></th>
                <th class="px-6 py-3 font-medium"><?= e('Email') ?></th>
                <th class="px-6 py-3 font-medium"><?= e('Balans') ?></th>
                <th class="px-6 py-3 font-medium"><?= e('Holat') ?></th>
                <th class="px-6 py-3 font-medium"><?= e('Ro\'yxatdan o\'tgan') ?></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            <?php foreach ($pagination['items'] as $u): ?>
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer" onclick="location.href='/admin/users/<?= $u['id'] ?>'">
                    <td class="px-6 py-3 font-medium text-slate-900 dark:text-slate-100"><?= e($u['name']) ?></td>
                    <td class="px-6 py-3 text-slate-600 dark:text-slate-400"><?= e($u['email']) ?></td>
                    <td class="px-6 py-3"><?= money((float) $u['balance']) ?></td>
                    <td class="px-6 py-3">
                        <span class="<?= $u['status'] === 'active' ? 'badge-green' : 'badge-red' ?>"><?= e($u['status'] === 'active' ? 'Faol' : 'Bloklangan') ?></span>
                    </td>
                    <td class="px-6 py-3 text-slate-500 dark:text-slate-400"><?= e($u['created_at']) ?></td>
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
