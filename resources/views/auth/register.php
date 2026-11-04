<h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-1"><?= e(__('auth.register_title')) ?></h1>
<p class="text-sm text-slate-500 dark:text-slate-400 mb-8"><?= e(__('auth.register_subtitle')) ?></p>

<form action="<?= route('/register') ?>" method="POST" class="space-y-5">
    <?= csrf_field() ?>

    <div>
        <label class="label" for="name"><?= e(__('auth.full_name')) ?></label>
        <input class="input" type="text" name="name" id="name" value="<?= old('name') ?>" required autofocus>
    </div>

    <div>
        <label class="label" for="email"><?= e(__('auth.email')) ?></label>
        <input class="input" type="email" name="email" id="email" value="<?= old('email') ?>" required>
    </div>

    <div>
        <label class="label" for="phone"><?= e(__('auth.phone')) ?></label>
        <input class="input" type="text" name="phone" id="phone" value="<?= old('phone') ?>" placeholder="998901234567">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="label" for="password"><?= e(__('auth.password')) ?></label>
            <input class="input" type="password" name="password" id="password" required>
        </div>
        <div>
            <label class="label" for="password_confirmation"><?= e(__('auth.password_confirm')) ?></label>
            <input class="input" type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
    </div>

    <button type="submit" class="btn-primary w-full py-3"><?= e(__('auth.submit_register')) ?></button>
</form>

<p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
    <?= e(__('auth.have_account')) ?> <a href="<?= route('/login') ?>" class="text-brand-600 dark:text-brand-400 font-semibold hover:underline"><?= e(__('nav.login')) ?></a>
</p>
