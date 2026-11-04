<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

    <div class="mb-14">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-slate-100 mb-3"><?= e(__('docs.title')) ?></h1>
        <p class="text-lg text-slate-500 dark:text-slate-400"><?= e(__('docs.subtitle')) ?></p>
    </div>

    <!-- Umumiy malumot -->
    <section class="mb-14">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-3"><?= e(__('docs.intro_title')) ?></h2>
        <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-5"><?= e(__('docs.intro_desc')) ?></p>
        <div class="card p-4 flex items-center justify-between">
            <span class="text-sm text-slate-500 dark:text-slate-400"><?= e(__('docs.base_url_label')) ?></span>
            <code class="text-sm font-mono text-brand-700 dark:text-brand-400">https://smsgram.uz/api/v1</code>
        </div>
    </section>

    <!-- Autentifikatsiya -->
    <section class="mb-14">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-3"><?= e(__('docs.auth_title')) ?></h2>
        <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-4"><?= e(__('docs.auth_desc')) ?></p>
        <div class="card bg-slate-900 border-0 p-4 mb-4">
            <code class="text-sm text-brand-400 font-mono"><?= e(__('docs.auth_header_example')) ?></code>
        </div>
        <a href="<?= route('/profile') ?>" class="inline-flex items-center gap-1.5 text-sm text-brand-600 dark:text-brand-400 font-medium hover:underline">
            <?= icon('code', 'w-4 h-4') ?> <?= e(__('docs.get_api_key')) ?>
        </a>
    </section>

    <!-- Ozgaruvchilar -->
    <section class="mb-14">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-3"><?= e(__('docs.variables_title')) ?></h2>
        <p class="text-slate-600 dark:text-slate-400 leading-relaxed"><?= e(__('docs.variables_desc')) ?></p>
    </section>

    <!-- Endpoint: Send SMS -->
    <section class="mb-14">
        <div class="flex items-center gap-3 mb-2">
            <span class="badge bg-brand-600 text-white">POST</span>
            <code class="text-sm font-mono text-slate-700 dark:text-slate-300">/api/v1/sms/send</code>
        </div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2"><?= e(__('docs.endpoint_send_title')) ?></h2>
        <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-6"><?= e(__('docs.endpoint_send_desc')) ?></p>

        <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-3"><?= e(__('docs.params_title')) ?></h3>
        <div class="card overflow-hidden mb-6">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 text-left">
                    <tr>
                        <th class="px-5 py-3 font-medium"><?= e(__('docs.param_col_name')) ?></th>
                        <th class="px-5 py-3 font-medium"><?= e(__('docs.param_col_type')) ?></th>
                        <th class="px-5 py-3 font-medium"><?= e(__('docs.param_col_required')) ?></th>
                        <th class="px-5 py-3 font-medium"><?= e(__('docs.param_col_desc')) ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr>
                        <td class="px-5 py-3 font-mono text-brand-700 dark:text-brand-400">template_id</td>
                        <td class="px-5 py-3 text-slate-500 dark:text-slate-400">integer</td>
                        <td class="px-5 py-3"><span class="badge-green"><?= e(__('docs.required_yes')) ?></span></td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400"><?= e(__('docs.param_template_id_desc')) ?></td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3 font-mono text-brand-700 dark:text-brand-400">recipients</td>
                        <td class="px-5 py-3 text-slate-500 dark:text-slate-400">string[]</td>
                        <td class="px-5 py-3"><span class="badge-green"><?= e(__('docs.required_yes')) ?></span></td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400"><?= e(__('docs.param_recipients_desc')) ?></td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3 font-mono text-brand-700 dark:text-brand-400">variables</td>
                        <td class="px-5 py-3 text-slate-500 dark:text-slate-400">object</td>
                        <td class="px-5 py-3"><span class="badge-yellow"><?= e(__('docs.required_no')) ?></span></td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400"><?= e(__('docs.param_variables_desc')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="grid sm:grid-cols-2 gap-4 mb-6">
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-400 dark:text-slate-500 mb-2">cURL</p>
                <div class="card bg-slate-900 border-0 p-4 overflow-x-auto h-full">
<pre class="text-xs text-slate-100 leading-relaxed"><code>curl -X POST https://smsgram.uz/api/v1/sms/send \
  -H "Authorization: Bearer sg_api_kalit" \
  -H "Content-Type: application/json" \
  -d '{
    "template_id": 1,
    "recipients": ["998901234567", "998907654321"],
    "variables": {
      "ism": "Malika",
      "code": "482913"
    }
  }'</code></pre>
                </div>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-400 dark:text-slate-500 mb-2"><?= e(__('docs.response_title')) ?></p>
                <div class="card bg-slate-900 border-0 p-4 overflow-x-auto h-full">
<pre class="text-xs text-slate-100 leading-relaxed"><code>{
  "success": true,
  "sms_id": 145,
  "provider_sms_id": "b7e1...",
  "recipients": 2,
  "error": null
}</code></pre>
                </div>
            </div>
        </div>

        <p class="text-xs font-medium text-slate-400 dark:text-slate-500 mb-2"><?= e(__('docs.response_error_title')) ?></p>
        <div class="card bg-slate-900 border-0 p-4 overflow-x-auto">
<pre class="text-xs text-slate-100 leading-relaxed"><code>{
  "success": false,
  "error": "variables.code maydoni shart"
}</code></pre>
        </div>
    </section>

    <!-- Endpoint: Balance -->
    <section class="mb-14">
        <div class="flex items-center gap-3 mb-2">
            <span class="badge bg-slate-700 text-white">GET</span>
            <code class="text-sm font-mono text-slate-700 dark:text-slate-300">/api/v1/balance</code>
        </div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-2"><?= e(__('docs.endpoint_balance_title')) ?></h2>
        <p class="text-slate-600 dark:text-slate-400 leading-relaxed mb-6"><?= e(__('docs.endpoint_balance_desc')) ?></p>

        <div class="grid sm:grid-cols-2 gap-4">
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-400 dark:text-slate-500 mb-2">cURL</p>
                <div class="card bg-slate-900 border-0 p-4 overflow-x-auto h-full">
<pre class="text-xs text-slate-100 leading-relaxed"><code>curl https://smsgram.uz/api/v1/balance \
  -H "Authorization: Bearer sg_api_kalit"</code></pre>
                </div>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium text-slate-400 dark:text-slate-500 mb-2"><?= e(__('docs.response_title')) ?></p>
                <div class="card bg-slate-900 border-0 p-4 overflow-x-auto h-full">
<pre class="text-xs text-slate-100 leading-relaxed"><code>{
  "success": true,
  "balance": 80000
}</code></pre>
                </div>
            </div>
        </div>
    </section>

    <!-- Xato kodlari -->
    <section class="mb-14">
        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 mb-4"><?= e(__('docs.errors_title')) ?></h2>
        <div class="card overflow-hidden">
            <table class="w-full text-sm">
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <tr>
                        <td class="px-5 py-3 font-mono text-red-600 w-20">401</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400"><?= e(__('docs.error_401_desc')) ?></td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3 font-mono text-red-600">402</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400"><?= e(__('docs.error_402_desc')) ?></td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3 font-mono text-red-600">403</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400"><?= e(__('docs.error_403_desc')) ?></td>
                    </tr>
                    <tr>
                        <td class="px-5 py-3 font-mono text-red-600">422</td>
                        <td class="px-5 py-3 text-slate-600 dark:text-slate-400"><?= e(__('docs.error_422_desc')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- CTA -->
    <?php if (!auth_check()): ?>
    <section class="rounded-3xl border border-slate-200 dark:border-slate-700 p-10 text-center">
        <p class="text-slate-500 dark:text-slate-400 mb-4"><?= e(__('docs.no_key_yet')) ?></p>
        <a href="<?= route('/register') ?>" class="btn-primary px-6 py-3"><?= e(__('docs.create_account_cta')) ?></a>
    </section>
    <?php endif; ?>

</div>
