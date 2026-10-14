@extends('layouts.app')

@section('title', __('forms.leads_title') . ' - ' . $certifier->name . ' - KosherMap')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-start justify-between gap-3 flex-wrap">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📥 {{ __('forms.leads_title') }}</h1>
            <p class="text-gray-500 text-sm mt-1">
                {!! __('forms.leads_intro', ['name' => '<strong>' . e($certifier->name) . '</strong>']) !!}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('account.certifiers.my') }}" class="text-sm text-blue-600 hover:underline">{{ __('forms.leads_back') }}</a>
            @if($leads->total() > 0)
            <a href="{{ route('account.certifiers.leads', ['export' => 'csv']) }}"
               class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 text-sm rounded-lg hover:bg-gray-50 transition">
                ⬇️ {{ __('forms.leads_export') }}
            </a>
            @endif
        </div>
    </div>

    @if($leads->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <p class="text-4xl mb-3">📭</p>
        <p class="font-medium">{{ __('forms.leads_empty') }}</p>
        <p class="text-sm mt-1">{{ __('forms.leads_empty_help') }}</p>
    </div>
    @else
    <p class="text-sm text-gray-500 mb-3">{{ trans_choice('forms.leads_count', $leads->total(), ['count' => number_format($leads->total())]) }}</p>

    <div class="space-y-3">
        @foreach($leads as $lead)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between gap-3 flex-wrap">
                <div>
                    <div class="font-bold text-gray-800">{{ $lead->company }}</div>
                    <div class="text-sm text-gray-600 mt-0.5">
                        {{ $lead->name }} ·
                        <a href="mailto:{{ $lead->email }}" class="text-blue-600 hover:underline">{{ $lead->email }}</a>
                        @if($lead->phone) · {{ $lead->phone }} @endif
                    </div>
                </div>
                <div class="text-xs text-gray-400 whitespace-nowrap">{{ $lead->created_at->format('d/m/Y H:i') }}</div>
            </div>

            @if($lead->product_type)
            <div class="mt-3 text-sm"><span class="text-gray-400">{{ __('forms.leads_product') }}</span> <span class="text-gray-700 font-medium">{{ $lead->product_type }}</span></div>
            @endif
            @if($lead->message)
            <div class="mt-1 text-sm text-gray-600 whitespace-pre-line break-words">{{ $lead->message }}</div>
            @endif
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $leads->links() }}</div>
    @endif
</div>
@endsection
