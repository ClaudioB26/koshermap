{{-- Suscripción al newsletter (doble confirmación). Las campañas se administran
     desde la notebook: ver doc/plan-marketing-brevo.md. --}}
<div id="newsletter" class="max-w-md mx-auto my-5 bg-gray-50 border border-gray-100 rounded-xl p-4 text-left">
    <p class="font-semibold text-gray-700 text-sm">✉️ {{ __('newsletter.title') }}</p>
    <p class="text-xs text-gray-500 mb-3">{{ __('newsletter.blurb') }}</p>

    @if(session('newsletter_status'))
        <p class="text-sm text-green-700 bg-green-50 border border-green-100 rounded-lg px-3 py-2">
            {{ __('newsletter.' . session('newsletter_status')) }}
        </p>
    @else
        @if($errors->has('email') || $errors->has('cf-turnstile-response'))
            <p class="text-xs text-red-600 mb-2">{{ $errors->first('email') ?: $errors->first('cf-turnstile-response') }}</p>
        @endif
        <form method="POST" action="{{ route('newsletter.store') }}" class="space-y-2">
            @csrf
            {{-- Honeypot --}}
            <div style="position:absolute; left:-9999px;" aria-hidden="true">
                <label>No completar<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            </div>
            <div class="flex gap-2">
                <input type="email" name="email" required value="{{ old('email') }}"
                       placeholder="{{ __('newsletter.placeholder') }}" aria-label="{{ __('newsletter.placeholder') }}"
                       class="flex-1 min-w-0 text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shrink-0">
                    {{ __('newsletter.button') }}
                </button>
            </div>
            @include('partials.turnstile')
            <p class="text-[11px] text-gray-400">{{ __('newsletter.privacy') }}</p>
        </form>
    @endif
</div>
