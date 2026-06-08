<div class="relative group">
    <button class="flex items-center gap-1 font-bold text-gray-700 hover:text-blue-600 bg-white px-3 py-1 rounded-full shadow-sm border border-gray-200 text-sm">
        <span class="text-lg">
            @switch(app()->getLocale())
                @case('en') 🇺🇸 @break
                @case('es') 🇪🇸 @break
                @case('pt') 🇧🇷 @break
                @case('fr') 🇫🇷 @break
                @case('he') 🇮🇱 @break
                @case('ru') 🇷🇺 @break
                @default 🌐
            @endswitch
        </span>
        <span class="hidden md:inline">{{ strtoupper(app()->getLocale()) }}</span>
        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
    </button>
    <div class="absolute right-0 rtl:right-auto rtl:left-0 mt-2 w-32 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block border border-gray-100">
        <a href="{{ route('set-locale', 'es') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇪🇸 Español
        </a>
        <a href="{{ route('set-locale', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇺🇸 English
        </a>
        <a href="{{ route('set-locale', 'pt') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇧🇷 Português
        </a>
        <a href="{{ route('set-locale', 'fr') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇫🇷 Français
        </a>
        <a href="{{ route('set-locale', 'he') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇮🇱 עברית
        </a>
        <a href="{{ route('set-locale', 'ru') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2">
            🇷🇺 Русский
        </a>
    </div>
</div>