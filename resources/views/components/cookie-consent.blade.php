<div x-data="cookieConsent()" x-show="show" x-cloak
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
     class="fixed bottom-0 inset-x-0 z-40 border-t border-ink-900/10 bg-white shadow-[0_-12px_40px_rgba(20,17,13,0.12)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="text-sm font-light leading-relaxed text-ink-900/70 text-center md:text-left">
            We use cookies to improve your browsing experience and analyse traffic. By continuing to use this site, you agree to our
            <a href="{{ route('privacy') }}" class="underline decoration-brass-400 underline-offset-4 text-brass-700 hover:text-ink-900">Privacy Policy</a>.
        </p>
        <div class="flex items-center gap-3 shrink-0">
            <button @click="accept()" class="btn-dark btn-sm whitespace-nowrap">Accept</button>
            <button @click="decline()" class="btn-secondary btn-sm whitespace-nowrap">Decline</button>
        </div>
    </div>
</div>

<script>
function cookieConsent() {
    return {
        show: false,
        init() {
            const v = localStorage.getItem('cookie_consent');
            if (!v) this.show = true;
        },
        accept() {
            localStorage.setItem('cookie_consent', 'accepted');
            this.show = false;
        },
        decline() {
            localStorage.setItem('cookie_consent', 'declined');
            this.show = false;
        }
    }
}
</script>
