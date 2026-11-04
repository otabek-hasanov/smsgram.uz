<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'Admin panel') ?></title>
    <meta name="robots" content="noindex,nofollow">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <meta name="theme-color" content="#0f172a">
    <link rel="stylesheet" href="<?= asset_url('build/app.css') ?>">
</head>
<body class="bg-slate-950 antialiased min-h-screen flex items-center justify-center px-4">

    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[560px] h-[560px] rounded-full bg-brand-600/20 blur-3xl"></div>
    </div>

    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-2 font-bold text-lg text-white">
                <span class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center text-sm"><?= logo_mark('w-4 h-4') ?></span>
                <?= e('Admin panel') ?>
            </span>
        </div>

        <div class="rounded-2xl bg-slate-900 border border-white/10 p-8 shadow-2xl">
            <?php if ($error = flash('error')): ?>
                <div class="mb-5 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm px-4 py-3"><?= e($error) ?></div>
            <?php endif; ?>
            <?php if ($success = flash('success')): ?>
                <div class="mb-5 rounded-xl bg-brand-500/10 border border-brand-500/20 text-brand-400 text-sm px-4 py-3"><?= e($success) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>

</body>
</html>
