<div class="grid md:grid-cols-2 gap-6">
    <?php foreach ($gateways as $gw): ?>
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-lg"><?= e($gw['name']) ?></h3>
                <span class="<?= $gw['status'] === 'active' ? 'badge-green' : 'badge-red' ?>"><?= e($gw['status']) ?></span>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 mb-4 break-all"><?= e($gw['base_uri']) ?></p>

            <form action="/admin/gateways/<?= $gw['id'] ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="label">Narx (SMS uchun)</label>
                    <input class="input" type="number" step="0.01" name="price_per_sms" value="<?= e((string) $gw['price_per_sms']) ?>">
                </div>
                <div>
                    <label class="label">Holat</label>
                    <select class="input" name="status">
                        <option value="active" <?= $gw['status'] === 'active' ? 'selected' : '' ?>>Faol</option>
                        <option value="inactive" <?= $gw['status'] === 'inactive' ? 'selected' : '' ?>>Nofaol</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full">Saqlash</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
