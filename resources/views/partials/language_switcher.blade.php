@php
    // En páginas de artículo (index o show) cada idioma tiene su propia URL
    // real, así que el selector tiene que navegar ahí en vez de solo cambiar
    // la sesión — si no, la ruta sin prefijo /articulos fuerza español de
    // nuevo apenas se recarga y el cambio de idioma "no hace nada".
    $isArticleShow = !empty($isArticleShowPage) && isset($article) && $article instanceof \App\Models\Article;
    $isArticleIndex = !$isArticleShow && !empty($isArticlesIndexPage);
    $articleOrNull = $isArticleShow ? $article : null;

    $localeHref = function (string $locale) use ($isArticleShow, $isArticleIndex, $articleOrNull) {
        if ($isArticleShow) {
            return $articleOrNull->urlFor($locale) ?? route('set-locale', $locale);
        }
        if ($isArticleIndex) {
            return \App\Models\Article::indexUrlFor($locale);
        }
        return route('set-locale', $locale);
    };
@endphp
<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open" type="button"
            class="flex items-center gap-1 font-bold text-gray-700 hover:text-blue-600 bg-white px-3 py-1 rounded-full shadow-sm border border-gray-200 text-sm">
        <span>{{ strtoupper(app()->getLocale()) }}</span>
        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    <div x-show="open" x-transition @click="open = false"
         class="absolute right-0 rtl:right-auto rtl:left-0 mt-2 w-32 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100"
         style="display: none;">
        <a href="{{ $localeHref('es') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇪🇸 Español
        </a>
        <a href="{{ $localeHref('en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇺🇸 English
        </a>
        <a href="{{ $localeHref('pt') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇧🇷 Português
        </a>
        <a href="{{ $localeHref('fr') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇫🇷 Français
        </a>
        <a href="{{ $localeHref('he') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇮🇱 עברית
        </a>
        <a href="{{ $localeHref('ru') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇷🇺 Русский
        </a>
    </div>
</div>
