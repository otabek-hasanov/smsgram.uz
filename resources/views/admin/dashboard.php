<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="card p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1"><?= e('Foydalanuvchilar') ?></p>
            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= (int) $stats['users_total'] ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0"><?= icon('users', 'w-5 h-5') ?></div>
    </div>
    <div class="card p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1"><?= e('Jami SMS') ?></p>
            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= (int) $stats['sms_total'] ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center justify-center shrink-0"><?= icon('send', 'w-5 h-5') ?></div>
    </div>
    <div class="card p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1"><?= e('Yetkazilgan SMS') ?></p>
            <p class="text-2xl font-bold text-brand-600 dark:text-brand-400"><?= (int) $stats['sms_delivered'] ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0"><?= icon('check-circle', 'w-5 h-5') ?></div>
    </div>
    <div class="card p-5 flex items-start justify-between">
        <div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-1"><?= e('Umumiy daromad') ?></p>
            <p class="text-2xl font-bold text-slate-900 dark:text-slate-100"><?= money((float) $stats['revenue']) ?></p>
        </div>
        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0"><?= icon('banknotes', 'w-5 h-5') ?></div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 font-semibold flex items-center justify-between">
            <span><?= e('Yangi foydalanuvchilar') ?></span>
            <a href="/admin/users" class="text-sm text-brand-600 dark:text-brand-400 hover:underline font-normal"><?= e('Foydalanuvchilar') ?></a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-left">
                <tr><th class="px-6 py-3 font-medium"><?= e('To\'liq ism') ?></th><th class="px-6 py-3 font-medium"><?= e('Balans') ?></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php foreach ($recentUsers as $u): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                        <td class="px-6 py-3">
                            <a href="/admin/users/<?= $u['id'] ?>" class="font-medium text-slate-900 dark:text-slate-100 hover:text-brand-600"><?= e($u['name']) ?></a>
                            <p class="text-xs text-slate-400 dark:text-slate-500"><?= e($u['email']) ?></p>
                        </td>
                        <td class="px-6 py-3"><?= money((float) $u['balance']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 font-semibold flex items-center justify-between">
            <span><?= e('So\'nggi SMS\'lar') ?></span>
            <a href="/admin/templates" class="text-sm text-brand-600 dark:text-brand-400 hover:underline font-normal"><?= e('Shablonlar') ?></a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-left">
                <tr><th class="px-6 py-3 font-medium"><?= e('Telefon') ?></th><th class="px-6 py-3 font-medium"><?= e('Qabul qiluvchilar') ?></th><th class="px-6 py-3 font-medium"><?= e('Holat') ?></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                <?php foreach ($recentSms as $sms): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800">
                        <td class="px-6 py-3 text-slate-600 dark:text-slate-400"><?= e($sms['user_name']) ?></td>
                        <td class="px-6 py-3 font-mono"><?= (int) $sms['recipients_count'] ?></td>
                        <td class="px-6 py-3"><span class="<?= sms_status_badge($sms['status']) ?>"><?= e($sms['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
