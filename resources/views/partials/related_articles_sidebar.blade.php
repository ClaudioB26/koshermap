@if($relatedArticles->isNotEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-lg font-bold text-gray-800 mb-4">📰 Te puede interesar</h2>
    <div class="flex flex-col gap-3">
        @foreach($relatedArticles as $article)
        <a href="{{ route('articles.show', $article->slug) }}"
           class="block bg-gray-50 hover:bg-blue-50 border border-gray-100 rounded-xl p-4 transition">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">
                {{ \App\Http\Controllers\ArticleController::CATEGORY_LABELS[$article->category] ?? $article->category }}
            </p>
            <p class="font-semibold text-gray-800 text-sm mb-1 leading-snug">{{ $article->title }}</p>
            <p class="text-xs text-gray-500 line-clamp-2">{{ $article->excerpt }}</p>
        </a>
        @endforeach
    </div>
</div>
@endif
