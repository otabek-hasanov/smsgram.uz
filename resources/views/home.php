<?php
$faqSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(static fn (int $i) => [
        '@type' => 'Question',
        'name' => __('home.faq' . $i . '_q'),
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => __('home.faq' . $i . '_a', ['price' => $price])],
    ], range(1, 7)),
];
?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "SMSGram",
    "applicationCategory": "BusinessApplication",
    "operatingSystem": "Web",
    "url": "https://smsgram.uz",
    "image": "https://smsgram.uz/og-image.png",
    "inLanguage": "<?= e(current_locale()) ?>",
    "description": <?= json_encode(__('seo.home_desc', ['price' => $price]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
    "keywords": <?= json_encode(__('seo.home_keywords'), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
    "offers": {
        "@type": "Offer",
        "price": "<?= (int) $price ?>",
        "priceCurrency": "UZS",
        "description": "Har bir SMS uchun narx"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "SMSGram",
    "alternateName": "SMS Gram",
    "url": "https://smsgram.uz",
    "logo": "https://smsgram.uz/icon-512.png",
    "image": "https://smsgram.uz/og-image.png",
    "email": "support@smsgram.uz",
    "areaServed": {
        "@type": "Country",
        "name": "Uzbekistan"
    },
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Toshkent",
        "addressCountry": "UZ"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+998-71-200-00-00",
        "contactType": "customer support",
        "email": "support@smsgram.uz",
        "areaServed": "UZ",
        "availableLanguage": ["uz", "ru", "en"]
    },
    "description": <?= json_encode(__('seo.home_desc', ['price' => $price]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "serviceType": "SMS Gateway",
    "name": "SMSGram SMS xizmati",
    "provider": {
        "@type": "Organization",
        "name": "SMSGram",
        "url": "https://smsgram.uz"
    },
    "areaServed": {
        "@type": "Country",
        "name": "Uzbekistan"
    },
    "audience": {
        "@type": "Audience",
        "audienceType": "Businesses, developers"
    },
    "offers": {
        "@type": "Offer",
        "price": "<?= (int) $price ?>",
        "priceCurrency": "UZS",
        "availability": "https://schema.org/InStock",
        "url": "https://smsgram.uz<?= route('/register') ?>"
    },
    "description": <?= json_encode(__('seo.home_desc', ['price' => $price]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "SMSGram",
            "item": "https://smsgram.uz<?= route() ?>"
        }
    ]
}
</script>
<script type="application/ld+json">
<?= json_encode($faqSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>

<section class="relative overflow-hidden">
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[640px] h-[640px] rounded-full bg-gradient-to-br from-brand-200 via-brand-100 to-transparent opacity-60 dark:opacity-10 dark:from-brand-500 dark:via-brand-700 blur-3xl"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 sm:pt-28 pb-16 text-center">
        <span class="badge-green mb-6 inline-flex items-center gap-1.5">
            <?= icon('bolt', 'w-3.5 h-3.5') ?>
            <?= e(__('home.badge')) ?>
        </span>
        <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 leading-[1.1]">
            <?= e(__('home.hero_title_1')) ?><br>
            <span class="bg-gradient-to-r from-brand-600 to-brand-500 bg-clip-text text-transparent"><?= e(__('home.hero_title_2')) ?></span>
        </h1>
        <p class="mt-6 text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
            <?= e(__('home.hero_desc')) ?>
        </p>
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?= route('/register') ?>" class="btn-primary text-base px-7 py-3.5 shadow-lg shadow-brand-600/25"><?= e(__('home.cta_start')) ?></a>
            <a href="<?= route('/docs') ?>" class="btn-secondary text-base px-7 py-3.5"><?= e(__('home.cta_api')) ?></a>
        </div>
    </div>
</section>

<section id="features" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid sm:grid-cols-3 gap-6">
        <div class="card p-7 hover:shadow-lg hover:-translate-y-0.5 transition-all">
            <div class="w-11 h-11 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-5">
                <?= icon('bolt', 'w-5 h-5') ?>
            </div>
            <h3 class="font-semibold text-lg mb-2"><?= e(__('home.feature1_title')) ?></h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"><?= e(__('home.feature1_desc')) ?></p>
        </div>
        <div class="card p-7 hover:shadow-lg hover:-translate-y-0.5 transition-all">
            <div class="w-11 h-11 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-5">
                <?= icon('code', 'w-5 h-5') ?>
            </div>
            <h3 class="font-semibold text-lg mb-2"><?= e(__('home.feature2_title')) ?></h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"><?= e(__('home.feature2_desc')) ?></p>
        </div>
        <div class="card p-7 hover:shadow-lg hover:-translate-y-0.5 transition-all">
            <div class="w-11 h-11 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-5">
                <?= icon('chart', 'w-5 h-5') ?>
            </div>
            <h3 class="font-semibold text-lg mb-2"><?= e(__('home.feature3_title')) ?></h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"><?= e(__('home.feature3_desc')) ?></p>
        </div>
    </div>
</section>

<section id="how" class="bg-slate-50 dark:bg-slate-800/60 py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-2xl sm:text-3xl font-bold mb-3"><?= e(__('home.how_title')) ?></h2>
            <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto"><?= e(__('home.how_desc')) ?></p>
        </div>
        <div class="grid sm:grid-cols-3 gap-8 relative">
            <div class="text-center relative">
                <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white flex items-center justify-center mx-auto mb-5 font-bold text-lg shadow-lg shadow-brand-600/30">1</div>
                <h3 class="font-semibold text-lg mb-2"><?= e(__('home.how_step1_title')) ?></h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-xs mx-auto"><?= e(__('home.how_step1_desc')) ?></p>
            </div>
            <div class="text-center relative">
                <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white flex items-center justify-center mx-auto mb-5 font-bold text-lg shadow-lg shadow-brand-600/30">2</div>
                <h3 class="font-semibold text-lg mb-2"><?= e(__('home.how_step2_title')) ?></h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-xs mx-auto"><?= e(__('home.how_step2_desc')) ?></p>
            </div>
            <div class="text-center relative">
                <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white flex items-center justify-center mx-auto mb-5 font-bold text-lg shadow-lg shadow-brand-600/30">3</div>
                <h3 class="font-semibold text-lg mb-2"><?= e(__('home.how_step3_title')) ?></h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed max-w-xs mx-auto"><?= e(__('home.how_step3_desc')) ?></p>
            </div>
        </div>
    </div>
</section>

<section id="usecases" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="text-center mb-14">
        <h2 class="text-2xl sm:text-3xl font-bold mb-3"><?= e(__('home.usecases_title')) ?></h2>
        <p class="text-slate-500 dark:text-slate-400 max-w-xl mx-auto"><?= e(__('home.usecases_desc')) ?></p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="card p-6">
            <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-4"><?= icon('shield', 'w-5 h-5') ?></div>
            <h3 class="font-semibold mb-1.5"><?= e(__('home.usecase1_title')) ?></h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"><?= e(__('home.usecase1_desc')) ?></p>
        </div>
        <div class="card p-6">
            <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-4"><?= icon('bell', 'w-5 h-5') ?></div>
            <h3 class="font-semibold mb-1.5"><?= e(__('home.usecase2_title')) ?></h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"><?= e(__('home.usecase2_desc')) ?></p>
        </div>
        <div class="card p-6">
            <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-4"><?= icon('card', 'w-5 h-5') ?></div>
            <h3 class="font-semibold mb-1.5"><?= e(__('home.usecase3_title')) ?></h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"><?= e(__('home.usecase3_desc')) ?></p>
        </div>
        <div class="card p-6">
            <div class="w-10 h-10 rounded-lg bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-4"><?= icon('send', 'w-5 h-5') ?></div>
            <h3 class="font-semibold mb-1.5"><?= e(__('home.usecase4_title')) ?></h3>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"><?= e(__('home.usecase4_desc')) ?></p>
        </div>
    </div>
</section>

<section id="pricing" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 to-brand-800 p-10 sm:p-14 text-center">
        <div class="pointer-events-none absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 20%, white 0, transparent 40%);"></div>
        <h2 class="text-2xl sm:text-3xl font-bold text-white mb-2"><?= e(__('home.pricing_title')) ?></h2>
        <p class="text-brand-50 mb-8 max-w-xl mx-auto"><?= e(__('home.pricing_desc')) ?></p>
        <div class="text-5xl sm:text-6xl font-extrabold text-white mb-8"><?= (int) $price ?> <span class="text-lg font-medium text-brand-100"><?= e(__('home.pricing_unit')) ?></span></div>
        <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 mb-9 text-sm text-brand-50">
            <span class="inline-flex items-center gap-2"><?= icon('check-circle', 'w-4 h-4') ?> <?= e(__('home.pricing_feature1')) ?></span>
            <span class="inline-flex items-center gap-2"><?= icon('check-circle', 'w-4 h-4') ?> <?= e(__('home.pricing_feature2')) ?></span>
            <span class="inline-flex items-center gap-2"><?= icon('check-circle', 'w-4 h-4') ?> <?= e(__('home.pricing_feature3')) ?></span>
        </div>
        <a href="<?= route('/register') ?>" class="btn bg-white dark:bg-slate-900 text-brand-700 dark:text-brand-400 hover:bg-brand-50 px-7 py-3.5 text-base shadow-lg"><?= e(__('home.pricing_cta')) ?></a>
    </div>
</section>

<section id="api" class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold mb-3"><?= e(__('home.api_title')) ?></h2>
            <p class="text-slate-600 dark:text-slate-400 mb-6 leading-relaxed"><?= e(__('home.api_desc')) ?></p>
            <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-400">
                <li class="flex items-center gap-2.5"><?= icon('check-circle', 'w-4 h-4 text-brand-600 dark:text-brand-400 shrink-0') ?> <?= e(__('home.api_feature1')) ?></li>
                <li class="flex items-center gap-2.5"><?= icon('check-circle', 'w-4 h-4 text-brand-600 dark:text-brand-400 shrink-0') ?> <?= e(__('home.api_feature2')) ?></li>
                <li class="flex items-center gap-2.5"><?= icon('check-circle', 'w-4 h-4 text-brand-600 dark:text-brand-400 shrink-0') ?> <?= e(__('home.api_feature3')) ?></li>
            </ul>
        </div>
        <div class="min-w-0 card bg-slate-900 border-0 p-6 overflow-x-auto shadow-xl">
<pre class="text-sm text-slate-100 leading-relaxed"><code>curl -X POST https://smsgram.uz/api/v1/sms/send \
  -H "Authorization: Bearer sg_sizning_api_kalitingiz" \
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
</section>

<section id="faq" class="bg-slate-50 dark:bg-slate-800/60 py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold mb-3"><?= e(__('home.faq_title')) ?></h2>
            <p class="text-slate-500 dark:text-slate-400"><?= e(__('home.faq_desc')) ?></p>
        </div>
        <div class="space-y-3">
            <?php for ($i = 1; $i <= 7; $i++): ?>
                <details class="card p-5 group">
                    <summary class="flex items-center justify-between cursor-pointer list-none font-medium text-slate-900 dark:text-slate-100">
                        <?= e(__('home.faq' . $i . '_q')) ?>
                        <?= icon('chevron-down', 'w-4 h-4 text-slate-400 dark:text-slate-500 group-open:rotate-180 transition-transform shrink-0 ml-4') ?>
                    </summary>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mt-3"><?= e(__('home.faq' . $i . '_a', ['price' => $price])) ?></p>
                </details>
            <?php endfor; ?>
        </div>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="rounded-3xl border border-slate-200 dark:border-slate-700 p-10 sm:p-14 text-center bg-white dark:bg-slate-900">
        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-100 mb-3"><?= e(__('home.cta_title')) ?></h2>
        <p class="text-slate-500 dark:text-slate-400 mb-8 max-w-xl mx-auto"><?= e(__('home.cta_desc')) ?></p>
        <a href="<?= route('/register') ?>" class="btn-primary text-base px-7 py-3.5 shadow-lg shadow-brand-600/25"><?= e(__('home.cta_button')) ?></a>
    </div>
</section>
