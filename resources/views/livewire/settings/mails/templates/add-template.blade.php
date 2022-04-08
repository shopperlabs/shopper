<div x-data="{ currentTab: 'html'}">
    <x-shopper::breadcrumb back="shopper.settings.users">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.mails')" title="Email configuration" />
    </x-shopper::breadcrumb>

    <div class="mt-3 border-b border-secondary-200 dark:border-secondary-700">
        <div class="sm:flex sm:items-baseline">
            <h3 class="text-xl leading-6 font-bold text-secondary-900 sm:text-2xl dark:text-white">
                {{ __('Add template') }}
            </h3>
            <div class="mt-4 sm:mt-0 sm:ml-10">
                <nav class="-mb-px flex space-x-8">
                    <button @click="currentTab = 'html'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" aria-current="page" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'html' }">
                        HTML
                    </button>

                    <button @click="currentTab = 'markdown'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'markdown' }">
                        Markdown
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-6 pb-10">
        <div x-show="currentTab === 'html'" class="grid grid-cols-1 gap-y-8 sm:grid-cols-2 sm:gap-x-5 sm:gap-y-6 md:grid-cols-3 lg:col-span-3">
            @foreach($skeletons->get('html') as $name => $subSkeletons)
                <button wire:click='$emit("openModal", "shopper-modals.choose-skeleton", {{ json_encode([$name, 'html', $subSkeletons]) }})' type="button" class="block overflow-hidden group text-left">
                    <figure>
                        <div class="relative rounded overflow-hidden transition transform duration-150 ease-in-out">
                            @if (file_exists(public_path("shopper/images/skeletons/html/{$name}.png")))
                                <img class="w-full h-44 object-top object-cover" src="{{ asset('shopper/images/skeletons/html/'.$name.'.png' ) }}" alt="{{ $name }}">
                            @elseif(file_exists(public_path( "shopper/images/skeletons/html/{$name}.jpg")))
                                <img class="w-full h-44 object-top object-cover" src="{{ asset('shopper/images/skeletons/html/'.$name.'.jpg' ) }}" alt="{{ $name }}">
                            @else
                                <img class="w-full h-44 object-top object-cover" src="{{ asset('shopper/images/skeletons/no-image.png' ) }}" alt="{{ $name }}">
                            @endif
                            <div class="absolute inset-0 flex items-center justify-center text-center rounded-md border-secondary-900 opacity-15 border"></div>
                            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-30 dark:bg-secondary-800 transition ease-in-out duration-150"></div>
                        </div>
                        <figcaption class="mt-3">
                            <p class="flex items-baseline text-sm font-medium text-secondary-900 capitalize dark:text-white">
                                <span> {{ $name }}</span>
                            </p>
                        </figcaption>
                    </figure>
                </button>
            @endforeach
        </div>
        <div x-cloak x-show="currentTab === 'markdown'" class="grid grid-cols-1 gap-y-8 sm:grid-cols-2 sm:gap-x-5 sm:gap-y-6 md:grid-cols-3 lg:col-span-3">
            @foreach($skeletons->get('markdown') as $name => $subSkeletons)
                <button wire:click='$emit("openModal", "shopper-modals.choose-skeleton", {{ json_encode([$name, 'markdown', $subSkeletons]) }})' type="button" class="block group text-left">
                    <figure>
                        <div class="relative rounded overflow-hidden transition transform duration-150 ease-in-out">
                            @if (file_exists(public_path("shopper/images/skeletons/markdown/{$name}.png")))
                                <img class="w-full h-44 object-top object-cover" src="{{ asset('shopper/images/skeletons/markdown/'.$name.'.png' ) }}" alt="{{ $name }}">
                            @elseif(file_exists(public_path( "shopper/images/skeletons/markdown/{$name}.jpg")))
                                <img class="w-full h-44 object-top object-cover" src="{{ asset('shopper/images/skeletons/markdown/'.$name.'.jpg' ) }}" alt="{{ $name }}">
                            @else
                                <img class="w-full h-44 object-top object-cover" src="{{ asset('shopper/images/skeletons/no-image.png' ) }}" alt="{{ $name }}">
                            @endif
                            <div class="absolute inset-0 flex items-center justify-center text-center rounded-md border-secondary-900 opacity-15 border"></div>
                            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-30 dark:bg-secondary-800 transition ease-in-out duration-150"></div>
                        </div>
                        <figcaption class="mt-3">
                            <p class="flex items-baseline text-sm font-medium text-secondary-900 capitalize dark:text-white">
                                <span> {{ $name }}</span>
                            </p>
                        </figcaption>
                    </figure>
                </button>
            @endforeach
        </div>
    </div>
</div>
