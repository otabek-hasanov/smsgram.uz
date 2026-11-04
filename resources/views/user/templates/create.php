<div class="max-w-xl">
    <div class="card p-6">
        <h2 class="font-semibold text-slate-900 dark:text-slate-100 mb-1"><?= e(__('templates.create_title')) ?></h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6"><?= e(__('templates.create_desc')) ?></p>

        <div class="mb-6 flex items-center justify-between rounded-xl bg-brand-50 dark:bg-brand-500/10 border border-brand-100 dark:border-brand-500/20 px-4 py-3">
            <span class="text-sm text-brand-700 dark:text-brand-400"><?= e(__('templates.price_note')) ?></span>
            <span class="font-bold text-brand-700 dark:text-brand-400"><?= money($price) ?></span>
        </div>

        <div class="mb-6 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0"><?= icon('code', 'w-4 h-4') ?></div>
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-1"><?= e(__('templates.variables_hint_title')) ?></p>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-3"><?= e(__('templates.variables_hint_desc')) ?></p>
                    <p class="text-xs font-medium text-slate-400 dark:text-slate-500 mb-1"><?= e(__('templates.variables_example_label')) ?></p>
                    <code class="block text-xs bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 rounded-lg px-3 py-2 text-slate-600 dark:text-slate-400"><?= e(__('templates.variables_example')) ?></code>
                </div>
            </div>
        </div>

        <form action="<?= route('/templates') ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <div>
                <label class="label" for="name"><?= e(__('templates.name')) ?></label>
                <input class="input" type="text" name="name" id="name" required>
            </div>

            <div>
                <label class="label" for="content"><?= e(__('templates.content')) ?></label>
                <textarea class="input font-mono" name="content" id="content" rows="6" maxlength="480" required placeholder="<?= e(__('templates.content_placeholder')) ?>"></textarea>
            </div>

            <button type="submit" class="btn-primary w-full"><?= e(__('templates.submit')) ?></button>
        </form>
    </div>
</div>
