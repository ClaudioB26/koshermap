@if($relatedArticles->isNotEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">📰 {{ __('related_articles_heading') }}</h2>
    <div class="flex flex-col gap-3">
        @foreach($relatedArticles as $relatedArticle)
        <a href="{{ $relatedArticle->urlFor(app()->getLocale()) ?? route('articles.show', $relatedArticle->slug) }}"
           class="block bg-gray-50 hover:bg-blue-50 border border-gray-100 rounded-xl p-4 transition">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">
                {{ \App\Http\Controllers\ArticleController::CATEGORY_LABELS[$relatedArticle->category] ?? $relatedArticle->category }}
            </p>
            <p class="font-semibold text-gray-800 text-sm mb-1 leading-snug">{{ $relatedArticle->title }}</p>
            <p class="text-xs text-gray-500 line-clamp-2">{{ $relatedArticle->excerpt }}</p>
        </a>
        @endforeach
    </div>
</div>
@endif
