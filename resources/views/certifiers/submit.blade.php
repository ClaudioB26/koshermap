@extends('layouts.app')

@section('title', __('forms.sub_title') . ' - KosherMap')
@section('robots', 'noindex, follow')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🏅 {{ __('forms.sub_title') }}</h1>
        <p class="text-gray-500 text-sm mt-1">
            {{ __('forms.sub_intro') }}
        </p>
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-800">
        <p class="font-semibold mb-1">{{ __('forms.sub_review') }}</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('certifiers.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
        @csrf

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('forms.sub_name') }} *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('forms.sub_rabbi') }}</label>
            <input type="text" name="rabbi_name" value="{{ old('rabbi_name') }}"
                   placeholder="{{ __('forms.sub_rabbi_ph') }}"
                   class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">{{ __('forms.sub_since') }}</label>
                <input type="number" name="founded_year" value="{{ old('founded_year') }}"
                       min="1900" max="{{ date('Y') }}" placeholder="{{ __('forms.sub_since_ph') }}"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">{{ __('forms.sub_website') }}</label>
                <input type="url" name="website" value="{{ old('website') }}" placeholder="https://"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('forms.sub_coverage') }} *</label>
            <p class="text-xs text-gray-400 mb-1">{{ __('forms.sub_coverage_help') }}</p>
            <textarea name="coverage_description" rows="3" required
                      class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">{{ old('coverage_description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('forms.sub_refs') }}</label>
            <p class="text-xs text-gray-400 mb-1">
                {{ __('forms.sub_refs_help') }}
            </p>
            <textarea name="reference_info" rows="3"
                      class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">{{ old('reference_info') }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">{{ __('forms.sub_docs') }}</label>
            <p class="text-xs text-gray-400 mb-1">
                {{ __('forms.sub_docs_help') }}
            </p>
            <div id="documents-list" class="space-y-2">
                <input type="file" name="documents[]" accept=".pdf,.jpg,.jpeg,.png,.webp"
                       class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
            </div>
            <button type="button" id="add-document-btn"
                    class="mt-2 text-sm text-blue-600 hover:underline">
                {{ __('forms.sub_add_doc') }}
            </button>
        </div>

        <div>
            <h2 class="text-sm font-semibold text-gray-700 mb-3">{{ __('forms.sub_contact') }}</h2>
            <p class="text-xs text-gray-400 mb-3">{{ __('forms.sub_contact_help') }}</p>
            <div class="space-y-3">
                <div class="text-sm text-gray-600 bg-gray-50 border border-gray-100 rounded-lg px-3 py-2">
                    {{ auth()->user()->name }} · {{ auth()->user()->email }}
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">{{ __('forms.sub_phone') }}</label>
                    <input type="text" name="submitted_by_phone" value="{{ old('submitted_by_phone') }}"
                           class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="flex items-start gap-2">
            <input type="checkbox" name="terms" id="terms" value="1" required
                   class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-300">
            <label for="terms" class="text-xs text-gray-500">
                {{ __('forms.sub_terms') }}
            </label>
        </div>

        <button type="submit"
                class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
            {{ __('forms.sub_submit') }}
        </button>
    </form>
</div>

<script>
document.getElementById('add-document-btn').addEventListener('click', function () {
    const list = document.getElementById('documents-list');
    if (list.children.length >= 5) {
        alert(@json(__('forms.sub_max_docs')));
        return;
    }
    const input = document.createElement('input');
    input.type = 'file';
    input.name = 'documents[]';
    input.accept = '.pdf,.jpg,.jpeg,.png,.webp';
    input.className = 'w-full text-sm border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-300 focus:outline-none';
    list.appendChild(input);
});
</script>

@endsection
