@auth
<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open" type="button"
            class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-blue-600 whitespace-nowrap">
        @if(auth()->user()->avatar)
        <img src="{{ auth()->user()->avatar }}" alt="" class="w-6 h-6 rounded-full">
        @else
        <span>👤</span>
        @endif
        <span class="hidden lg:inline">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 16) }}</span>
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div x-show="open" x-transition @click="open = false"
         class="absolute right-0 rtl:right-auto rtl:left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100"
         style="display: none;">
        <a href="{{ route('account.places') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
            🏪 {{ __('my_places') }}
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                {{ __('logout_link') }}
            </button>
        </form>
    </div>
</div>
@else
<a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 whitespace-nowrap">
    {{ __('login_link') }}
</a>
@endauth
