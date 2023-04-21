<div class="flex-1 overflow-y-auto hide-scroll">
    <h4 class="p-4 text-xs leading-4 text-secondary-900 uppercase tracking-wider font-bold sm:py-5 dark:text-white">
        {{ __('shopper::layout.account_dropdown.settings') }}
    </h4>

    <nav aria-label="Setting menu" role="navigation" class="border-t border-secondary-200 space-y-8 dark:border-secondary-700">
        <ul class="border-b border-secondary-200 divide-y divide-secondary-200 dark:divide-secondary-700 dark:border-secondary-700">
            @foreach(config('shopper.settings.items') as $menu)
                <x-shopper::menu.nav-setting :menu="$menu" />
            @endforeach
        </ul>
    </nav>
</div>
