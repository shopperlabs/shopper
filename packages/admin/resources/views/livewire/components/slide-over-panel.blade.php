<section
    x-data="SlideOverPanel()"
    x-on:close.stop="setShowPropertyTo(false)"
    x-on:keydown.escape.window="closePanelOnEscape()"
    x-show="open"
    class="relative z-50"
    x-ref="dialog"
    aria-modal="true"
    x-cloak
>
    <div
        x-cloak
        x-show="open"
        x-on:click="closePanelOnClickAway()"
        x-transition:enter="duration-500 ease-in-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="duration-500 ease-in-out"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-950/50 dark:bg-gray-950/75 backdrop-blur-sm transition-opacity"
    ></div>

    <div class="fixed inset-0">
        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 pr-2 py-2">
            <div
                x-cloak
                x-show="open && showActiveComponent"
                x-transition:enter="transform transition duration-500 ease-in-out"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition duration-500 ease-in-out"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="pointer-events-auto w-screen"
                x-bind:class="panelWidth"
                x-trap.noscroll.inert="open && showActiveComponent"
                @click.away="closePanelOnClickAway()"
                aria-modal="true"
            >
                <div class="h-full bg-white shadow-xl rounded-xl overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10 dark:bg-gray-900">
                    @forelse ($components as $id => $component)
                        <div
                            class="h-full"
                            x-show.immediate="activeComponent == '{{ $id }}'"
                            x-ref="{{ $id }}"
                            wire:key="{{ $id }}"
                        >
                            @livewire($component['name'], $component['arguments'], key($id))
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
