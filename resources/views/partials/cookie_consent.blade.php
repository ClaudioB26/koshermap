<div id="cookie-consent-banner"
     class="hidden fixed bottom-0 left-0 right-0 z-[100] bg-white border-t border-gray-200 shadow-lg">
    <div class="container mx-auto px-4 py-4 flex flex-col sm:flex-row items-center gap-3">
        <p class="text-sm text-gray-600 flex-grow">
            {{ __('cookies.text') }}
            <a href="{{ route('pages.privacidad') }}" class="text-blue-600 hover:underline">{{ __('cookies.link') }}</a>
        </p>
        <div class="flex gap-2 shrink-0">
            <button id="cookie-consent-reject" type="button"
                    class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                {{ __('cookies.reject') }}
            </button>
            <button id="cookie-consent-accept" type="button"
                    class="px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                {{ __('cookies.accept') }}
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    function getCookie(name) {
        const match = document.cookie.match('(?:^|; )' + name + '=([^;]*)');
        return match ? decodeURIComponent(match[1]) : null;
    }
    function setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/; SameSite=Lax';
    }

    const banner = document.getElementById('cookie-consent-banner');
    if (!banner) return;

    if (!getCookie('cookie_consent')) {
        banner.classList.remove('hidden');
    }

    document.getElementById('cookie-consent-accept').addEventListener('click', function () {
        setCookie('cookie_consent', 'accepted', 365);
        if (typeof window.loadAnalyticsAndAds === 'function') {
            window.loadAnalyticsAndAds();
        }
        banner.classList.add('hidden');
    });

    document.getElementById('cookie-consent-reject').addEventListener('click', function () {
        setCookie('cookie_consent', 'rejected', 365);
        banner.classList.add('hidden');
    });
})();
</script>
