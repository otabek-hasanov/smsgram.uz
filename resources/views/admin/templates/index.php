<div class="space-y-5">
    <?php foreach ($templates as $tpl): ?>
        <div class="card p-6">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-slate-100">
                        <?= e($tpl['name']) ?>
                        <span class="ml-1.5 font-mono text-xs text-slate-400 dark:text-slate-500">ID: <?= (int) $tpl['id'] ?></span>
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500"><?= e('Muallif') ?>: <?= e($tpl['user_name']) ?> (<?= e($tpl['user_email']) ?>)</p>
                </div>
                <span class="<?= $tpl['status'] === 'approved' ? 'badge-green' : ($tpl['status'] === 'rejected' ? 'badge-red' : 'badge-yellow') ?>">
                    <?= e(match ($tpl['status']) { 'approved' => 'Tasdiqlangan', 'rejected' => 'Rad etilgan', default => 'Tekshirilmoqda' }) ?>
                </span>
            </div>

            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-3"><?= e($tpl['content']) ?></p>

            <?php $vars = \App\Models\SmsTemplate::extractVariables($tpl['content']); ?>
            <?php if (!empty($vars)): ?>
                <div class="flex flex-wrap gap-1.5 mb-4">
                    <?php foreach ($vars as $v): ?>
                        <code class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-md px-2 py-0.5">{<?= e($v) ?>}</code>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($tpl['status'] === 'pending'): ?>
                <div class="grid sm:grid-cols-2 gap-4">
                    <form action="/admin/templates/<?= $tpl['id'] ?>/approve" method="POST" class="flex gap-2">
                        <?= csrf_field() ?>
                        <input class="input" type="text" name="provider_template_id" placeholder="Provider template ID (ixtiyoriy)">
                        <button type="submit" class="btn-primary shrink-0 inline-flex items-center gap-1.5">
                            <?= icon('check-circle', 'w-4 h-4') ?> <?= e('Tasdiqlash') ?>
                        </button>
                    </form>
                    <form action="/admin/templates/<?= $tpl['id'] ?>/reject" method="POST" class="flex gap-2">
                        <?= csrf_field() ?>
                        <input class="input" type="text" name="admin_note" placeholder="<?= e('Admin izohi') ?>">
                        <button type="submit" class="btn-danger shrink-0 inline-flex items-center gap-1.5">
                            <?= icon('x-circle', 'w-4 h-4') ?> <?= e('Rad etish') ?>
                        </button>
                    </form>
                </div>
            <?php elseif ($tpl['status'] === 'rejected' && !empty($tpl['admin_note'])): ?>
                <p class="text-xs text-red-500 dark:text-red-400"><?= e('Admin izohi') ?>: <?= e($tpl['admin_note']) ?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
