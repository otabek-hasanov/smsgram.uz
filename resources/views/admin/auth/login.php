<h1 class="text-xl font-bold text-white mb-1 text-center"><?= e('Admin kirish') ?></h1>
<p class="text-sm text-slate-400 dark:text-slate-500 mb-8 text-center"><?= e('Bu panel faqat administratorlar uchun') ?></p>

<form action="/admin/login" method="POST" class="space-y-5">
    <?= csrf_field() ?>

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5" for="username"><?= e('Foydalanuvchi nomi') ?></label>
        <input class="w-full rounded-lg border border-white/10 bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 focus:outline-none"
               type="text" name="username" id="username" required autofocus autocomplete="username">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-300 mb-1.5" for="password"><?= e('Parol') ?></label>
        <input class="w-full rounded-lg border border-white/10 bg-white/5 px-3.5 py-2.5 text-sm text-white placeholder-slate-500 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/30 focus:outline-none"
               type="password" name="password" id="password" required autocomplete="current-password">
    </div>

    <button type="submit" class="btn-primary w-full py-3"><?= e('Kirish') ?></button>
</form>
