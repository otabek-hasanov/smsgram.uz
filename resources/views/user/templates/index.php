<div class="flex items-center justify-between mb-6">
    <h2 class="font-semibold text-slate-900 dark:text-slate-100"><?= e(__('templates.my_templates')) ?></h2>
    <a href="<?= route('/templates/create') ?>" class="btn-primary inline-flex items-center gap-2">
        <?= icon('plus', 'w-4 h-4') ?> <?= e(__('templates.create_new')) ?>
    </a>
</div>

<?php if (empty($templates)): ?>
    <div class="card p-12 text-center">
        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-4"><?= icon('template', 'w-6 h-6') ?></div>
        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-1"><?= e(__('templates.empty')) ?></h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-5"><?= e(__('templates.empty_desc')) ?></p>
        <a href="<?= route('/templates/create') ?>" class="btn-primary inline-flex items-center gap-2">
            <?= icon('plus', 'w-4 h-4') ?> <?= e(__('templates.create_new')) ?>
        </a>
    </div>
<?php else: ?>
    <div class="grid md:grid-cols-2 gap-5">
        <?php foreach ($templates as $tpl): ?>
            <div class="card p-6">
                <div class="flex items-start justify-between gap-3 mb-1">
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100"><?= e($tpl['name']) ?></h3>
                    <span class="<?= $tpl['status'] === 'approved' ? 'badge-green' : ($tpl['status'] === 'rejected' ? 'badge-red' : 'badge-yellow') ?> shrink-0">
                        <?= e(__('templates.status_' . $tpl['status'])) ?>
                    </span>
                </div>
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-3 font-mono">ID: <?= (int) $tpl['id'] ?></p>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-3"><?= e($tpl['content']) ?></p>
                <?php $vars = \App\Models\SmsTemplate::extractVariables($tpl['content']); ?>
                <?php if (!empty($vars)): ?>
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        <?php foreach ($vars as $v): ?>
                            <code class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-md px-2 py-0.5">{<?= e($v) ?>}</code>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($tpl['status'] === 'rejected' && !empty($tpl['admin_note'])): ?>
                    <p class="text-xs text-red-500 dark:text-red-400 mb-3"><?= e(__('templates.admin_note')) ?>: <?= e($tpl['admin_note']) ?></p>
                <?php endif; ?>
                <p class="text-xs text-slate-400 dark:text-slate-500"><?= e($tpl['created_at']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
