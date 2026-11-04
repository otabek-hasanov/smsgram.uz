<?php if (empty($templates)): ?>
    <div class="card p-12 text-center max-w-xl mx-auto">
        <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mx-auto mb-4"><?= icon('template', 'w-6 h-6') ?></div>
        <h3 class="font-semibold text-slate-900 dark:text-slate-100 mb-1"><?= e(__('sms.no_templates_title')) ?></h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-5"><?= e(__('sms.no_templates_desc')) ?></p>
        <a href="<?= route('/templates/create') ?>" class="btn-primary inline-flex items-center gap-2">
            <?= icon('plus', 'w-4 h-4') ?> <?= e(__('sms.create_template_link')) ?>
        </a>
    </div>
<?php else: ?>
    <div class="max-w-xl">
        <div class="card p-6">
            <form action="<?= route('/sms/send') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <div>
                    <label class="label" for="template_id"><?= e(__('sms.select_template')) ?></label>
                    <select class="input" name="template_id" id="template_id" required>
                        <?php foreach ($templates as $tpl): ?>
                            <option value="<?= (int) $tpl['id'] ?>" data-variables="<?= e(implode(',', $tpl['variables'])) ?>">#<?= (int) $tpl['id'] ?> — <?= e($tpl['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="variables-wrap" class="hidden space-y-4 rounded-xl border border-slate-200 dark:border-slate-700 p-4">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400"><?= e(__('templates.fill_variables')) ?></p>
                    <div id="variables-container" class="space-y-3"></div>
                </div>

                <div>
                    <label class="label" for="recipients"><?= e(__('sms.recipients_label')) ?></label>
                    <textarea class="input font-mono" name="recipients" id="recipients" rows="5" required placeholder="<?= e(__('sms.recipients_placeholder')) ?>"></textarea>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1"><?= e(__('sms.recipients_help')) ?></p>
                </div>

                <button type="submit" class="btn-primary w-full inline-flex items-center justify-center gap-2">
                    <?= icon('send', 'w-4 h-4') ?> <?= e(__('sms.submit')) ?>
                </button>
            </form>
        </div>
    </div>

    <script>
    (function () {
        var select = document.getElementById('template_id');
        var wrap = document.getElementById('variables-wrap');
        var container = document.getElementById('variables-container');

        function renderVariableFields() {
            var option = select.options[select.selectedIndex];
            var raw = (option && option.dataset.variables) ? option.dataset.variables : '';
            var vars = raw.split(',').filter(Boolean);

            container.innerHTML = '';

            if (vars.length === 0) {
                wrap.classList.add('hidden');
                return;
            }

            wrap.classList.remove('hidden');

            vars.forEach(function (name) {
                var field = document.createElement('div');

                var label = document.createElement('label');
                label.className = 'label';
                label.textContent = '{' + name + '}';

                var input = document.createElement('input');
                input.className = 'input';
                input.type = 'text';
                input.name = 'variables[' + name + ']';
                input.required = true;

                field.appendChild(label);
                field.appendChild(input);
                container.appendChild(field);
            });
        }

        select.addEventListener('change', renderVariableFields);
        renderVariableFields();
    })();
    </script>
<?php endif; ?>
