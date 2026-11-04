<script>
(function () {
    window.getThemePref = function () {
        try { return localStorage.getItem('theme') || 'auto'; } catch (e) { return 'auto'; }
    };

    window.applyTheme = function (pref) {
        var isDark = pref === 'dark' || (pref === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        document.documentElement.classList.toggle('dark', isDark);

        document.querySelectorAll('[data-theme-btn]').forEach(function (btn) {
            var active = btn.getAttribute('data-theme-btn') === pref;
            btn.classList.toggle('bg-white', active);
            btn.classList.toggle('dark:bg-slate-700', active);
            btn.classList.toggle('shadow-sm', active);
            btn.classList.toggle('text-brand-600', active);
            btn.classList.toggle('dark:text-brand-400', active);
            btn.classList.toggle('text-slate-400', !active);
            btn.classList.toggle('dark:text-slate-500', !active);
        });
    };

    window.setTheme = function (pref) {
        try { localStorage.setItem('theme', pref); } catch (e) {}
        window.applyTheme(pref);
    };

    window.applyTheme(window.getThemePref());

    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () {
            if (window.getThemePref() === 'auto') {
                window.applyTheme('auto');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        window.applyTheme(window.getThemePref());
    });
})();
</script>
