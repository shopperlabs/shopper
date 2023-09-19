@props(['menu'])

<li class="relative py-4 px-3 hover:bg-secondary-50 dark:hover:bg-secondary-700 focus-within:ring-2 focus-within:ring-inset focus-within:ring-primary-600 {{ $menu['route'] ? active([$menu['route'] . '*'], 'bg-secondary-50 dark:bg-secondary-800') : '' }}">
    <div class="flex items-start justify-between space-x-3">
        <span class="shrink-0 {{ $menu['route'] ? active([$menu['route'] . '*'], 'text-primary-500', 'text-secondary-400 dark:text-secondary-500') : 'text-secondary-400 dark:text-secondary-500' }}">
            <x-dynamic-component :component="$menu['icon']" class="h-6 w-6" />
        </span>
        <div class="min-w-0 flex-1">
            <a href="{{ $menu['route'] ? route($menu['route']) : '#' }}" type="button" class="block text-left focus:outline-none">
                <span class="absolute inset-0" aria-hidden="true"></span>
                <p class="inline-flex items-center text-sm font-medium text-secondary-900 truncate dark:text-white {{ $menu['route'] ? active([$menu['route'] . '*'], 'font-bold') : '' }}">
                    {{ __($menu['name']) }}

                    @if(! $menu['route'])
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-secondary-100 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-300">
                            <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-secondary-400 dark:text-secondary-500" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            {{ __('shopper::layout.soon') }}
                        </span>
                    @endif
                </p>
                <div class="mt-1">
                    <p class="line-clamp-2 text-sm text-secondary-500 dark:text-secondary-400">
                        {{ __($menu['description']) }}
                    </p>
                </div>
            </a>
        </div>
    </div>
</li>
