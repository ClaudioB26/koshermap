{{--
    Uso:
    @include('partials.report_form', [
        'route'   => route('products.report', $product),  // o places.report
        'reasons' => \App\Models\Report::REASONS_PRODUCT, // o REASONS_PLACE
        'label'   => $product->name,                       // nombre del item
    ])
--}}

@if(session('report_sent'))
<div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800">
    ✅ {{ __('report.thank_you') }}
</div>
@else
<div x-data="{ open: false }" class="mt-6">
    <button @click="open = !open"
            type="button"
            class="flex items-center gap-1.5 text-sm text-gray-400 hover:text-red-500 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        {{ __('report.report_problem', ['name' => $label]) }}
    </button>

    <div x-show="open" x-transition class="mt-3 bg-red-50 border border-red-100 rounded-xl p-5">
        <h4 class="text-sm font-semibold text-red-700 mb-4">{{ __('report.what_is_the_issue') }}</h4>

        <form method="POST" action="{{ $route }}" class="space-y-3">
            @csrf

            <div>
                <select name="reason" required
                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-red-300 focus:outline-none">
                    <option value="">{{ __('report.select_reason') }}</option>
                    @foreach($reasons as $value => $label_reason)
                    <option value="{{ $value }}">{{ $label_reason }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <textarea name="observation" rows="3" placeholder="{{ __('report.observation') }}"
                          class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-red-300 focus:outline-none resize-none"></textarea>
            </div>

            <div>
                <input type="email" name="email" placeholder="{{ __('report.email_placeholder') }}"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-red-300 focus:outline-none">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition font-medium">
                    {{ __('report.submit') }}
                </button>
                <button type="button" @click="open = false"
                        class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 transition">
                    {{ __('report.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endif
