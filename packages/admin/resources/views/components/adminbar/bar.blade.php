@php
    $user = shopper()->auth()->user();
@endphp

@if (
    shopper()->auth()->check()
    && $user
    && ($user->isAdmin() || $user->isManager())
)
    <div
        x-show="barOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-0 left-0 z-30 h-dvh"
    >
        <div class="h-full max-h-full p-1 duration-300 ease-out">
            <div class="w-8 pb-1 rounded-md bg-gray-950/90 backdrop-blur-xl dark:bg-gray-50/90">
                <div class="flex items-center justify-center size-8 overflow-hidden group">
                    <button :class="{ 'translate-x-full' : iframeOpen }" x-on:click="barOpen=false" class="flex items-center justify-center size-8 duration-300 ease-out">
                        <svg class="relative shrink-0 size-4 duration-300 ease-out opacity-60 group-hover:opacity-100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M3.74963 12H20.2496M3.74963 12L10.2496 18.25M3.74963 12L10.2496 5.75" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <button :class="{ '-translate-x-full' : !iframeOpen }" x-on:click="iframeOpen=false" class="absolute flex items-center justify-center size-8 duration-300 ease-out">
                        <svg class="relative shrink-0 size-4 duration-300 ease-out opacity-60 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <a
                    href="https://laravelshopper.dev"
                    target="_blank"
                    class="relative group dark:text-gray-950/60 dark:hover:text-gray-950 text-white/70 hover:text-white"
                >
                    <span class="relative z-20 flex items-center justify-center size-8 dark:group-hover:bg-gradient-to-r dark:group-hover:from-gray-50 dark:group-hover:to-white group-hover:bg-gradient-to-r from-gray-950 to-gray-800">
                        <x-phosphor-notebook-duotone class="text-white size-4 group-hover:translate-x-0.5 ease-out duration-300" aria-hidden="true" />
                    </span>
                    <span class="absolute top-0 z-10 flex items-center object-right h-8 pl-2 pr-3 text-xs duration-300 ease-out translate-x-6 bg-gray-800 rounded-r opacity-0 pointer-events-none dark:bg-white group-hover:pointer-events-auto group-hover:translate-x-7 group-hover:opacity-100">
                        {{ __('shopper::pages/dashboard.cards.doc_title') }}
                    </span>
                </a>

                <button
                    x-on:click="iframeURL='{{ route('shopper.dashboard') }}'; iframeOpen=!(section=='admin' && iframeOpen);  section='admin';"
                    :class="{
                        'text-white dark:text-gray-950' : section == 'admin',
                        'dark:text-gray-950/60 dark:hover:text-gray-950 text-white/70 hover:text-white' : section != 'admin'
                    }"
                    class="relative group"
                >
                    <span class="relative z-20 flex items-center justify-center size-8 dark:group-hover:bg-gradient-to-r dark:group-hover:from-gray-50 dark:group-hover:to-white group-hover:bg-gradient-to-r from-gray-950 to-gray-800">
                        <x-shopper::brand class="size-4 group-hover:translate-x-0.5 ease-out duration-300" aria-hidden="true" />
                    </span>
                    <span class="absolute top-0 z-10 flex items-center object-right h-8 pl-2 pr-3 text-xs duration-300 ease-out translate-x-6 bg-gray-800 rounded-r opacity-0 pointer-events-none dark:bg-white group-hover:pointer-events-auto group-hover:translate-x-7 group-hover:opacity-100">
                        {{ __('shopper::pages/dashboard.menu') }}
                    </span>
                </button>

                <form method="POST" action="{{ route('shopper.logout') }}" class="w-full">
                    @csrf
                    <button
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="relative group text-white/70 hover:text-white dark:text-gray-950/60 dark:hover:text-gray-950"
                    >
                        <span class="relative z-20 flex items-center justify-center size-8 dark:group-hover:bg-gradient-to-r dark:group-hover:from-gray-50 dark:group-hover:to-white group-hover:bg-gradient-to-r from-gray-950 to-gray-800">
                            <x-untitledui-log-out
                                class="size-4 group-hover:translate-x-0.5 ease-out duration-300"
                                aria-hidden="true"
                            />
                        </span>
                        <span class="absolute top-0 z-10 flex items-center shrink-0 object-right h-8 pl-2 pr-3 text-xs duration-300 ease-out translate-x-6 bg-gray-800 rounded-r opacity-0 pointer-events-none dark:bg-white group-hover:pointer-events-auto group-hover:translate-x-7 group-hover:opacity-100">
                            {{ __('shopper::words.log_out') }}
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endif
