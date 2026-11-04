<!DOCTYPE html>
<html lang="<?= e(current_locale()) ?>">
<head>
    <meta charset="UTF-8">
    <?php view('partials.theme-init'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — <?= e(__('errors.404_title')) ?></title>
    <link rel="stylesheet" href="<?= asset_url('build/app.css') ?>">
</head>
<body class="bg-slate-50 dark:bg-slate-950 min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <p class="text-7xl font-extrabold text-brand-600 dark:text-brand-400 mb-4">404</p>
        <h1 class="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-2"><?= e(__('errors.404_title')) ?></h1>
        <p class="text-slate-500 dark:text-slate-400 mb-6"><?= e(__('errors.404_desc')) ?></p>
        <a href="<?= route() ?>" class="btn-primary"><?= e(__('errors.back_home')) ?></a>
    </div>
</body>
</html>
