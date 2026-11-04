<h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-1"><?= e(__('auth.login_title')) ?></h1>
<p class="text-sm text-slate-500 dark:text-slate-400 mb-8"><?= e(__('auth.login_subtitle')) ?></p>

<form action="<?= route('/login') ?>" method="POST" class="space-y-5">
    <?= csrf_field() ?>

    <div>
        <label class="label" for="email"><?= e(__('auth.email')) ?></label>
        <input class="input" type="email" name="email" id="email" value="<?= old('email') ?>" required autofocus>
    </div>

    <div>
        <label class="label" for="password"><?= e(__('auth.password')) ?></label>
        <input class="input" type="password" name="password" id="password" required>
    </div>

    <button type="submit" class="btn-primary w-full py-3"><?= e(__('auth.submit_login')) ?></button>
</form>

<p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
    <?= e(__('auth.no_account')) ?> <a href="<?= route('/register') ?>" class="text-brand-600 dark:text-brand-400 font-semibold hover:underline"><?= e(__('nav.register')) ?></a>
</p>
