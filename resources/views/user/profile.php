<div class="grid lg:grid-cols-2 gap-6">
    <div class="card p-6">
        <h2 class="font-semibold mb-5"><?= e(__('profile.personal_info')) ?></h2>
        <form action="<?= route('/profile') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="label" for="name"><?= e(__('auth.full_name')) ?></label>
                <input class="input" type="text" name="name" id="name" value="<?= e($user['name']) ?>">
            </div>
            <div>
                <label class="label"><?= e(__('profile.email_locked')) ?></label>
                <input class="input bg-slate-50 dark:bg-slate-800/60" type="email" value="<?= e($user['email']) ?>" disabled>
            </div>
            <div>
                <label class="label" for="phone"><?= e(__('auth.phone')) ?></label>
                <input class="input" type="text" name="phone" id="phone" value="<?= e($user['phone'] ?? '') ?>">
            </div>
            <button type="submit" class="btn-primary w-full"><?= e(__('common.save')) ?></button>
        </form>
    </div>

    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold"><?= e(__('profile.api_keys')) ?></h2>
        </div>

        <form action="<?= route('/profile/api-keys') ?>" method="POST" class="flex gap-2 mb-6">
            <?= csrf_field() ?>
            <input class="input" type="text" name="name" placeholder="<?= e(__('profile.key_name_placeholder')) ?>" required>
            <button type="submit" class="btn-primary shrink-0"><?= e(__('profile.create_key')) ?></button>
        </form>

        <div class="space-y-3">
            <?php if (empty($apiKeys)): ?>
                <p class="text-sm text-slate-400 dark:text-slate-500"><?= e(__('profile.no_keys')) ?></p>
            <?php endif; ?>
            <?php foreach ($apiKeys as $key): ?>
                <div class="rounded-xl border border-slate-200 dark:border-slate-700 p-3">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100"><?= e($key['name']) ?></p>
                        <span class="text-xs text-slate-400 dark:text-slate-500"><?= e($key['last_used_at'] ?? __('profile.never_used')) ?></span>
                    </div>
                    <code class="text-xs text-slate-500 dark:text-slate-400 break-all"><?= e($key['key_value']) ?></code>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
