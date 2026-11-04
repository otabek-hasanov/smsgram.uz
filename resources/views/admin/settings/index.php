<div class="max-w-lg">
    <div class="card p-6">
        <form action="/admin/settings" method="POST" class="space-y-5">
            <?= csrf_field() ?>

            <div>
                <label class="label" for="template_price"><?= e('Shablon qo\'shish narxi (so\'m)') ?></label>
                <input class="input" type="number" step="0.01" name="template_price" id="template_price" value="<?= e((string) $templatePrice) ?>">
            </div>

            <div>
                <label class="label" for="price_per_sms"><?= e('Har bir SMS narxi (so\'m)') ?></label>
                <input class="input" type="number" step="0.01" name="price_per_sms" id="price_per_sms" value="<?= e((string) $pricePerSms) ?>">
            </div>

            <button type="submit" class="btn-primary w-full"><?= e('Sozlamalarni saqlash') ?></button>
        </form>
    </div>
</div>
