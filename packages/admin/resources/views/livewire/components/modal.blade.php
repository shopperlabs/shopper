<div
    x-data="modal()"
    x-on:close.stop="setShowPropertyTo(false)"
    x-on:keydown.escape.window="closeModalOnEscape()"
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto"
    x-cloak
>
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-10 text-center sm:block sm:p-0">
        <div
            x-show="show"
            x-on:click="closeModalOnClickAway()"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-all transform"
        >
            <div class="absolute inset-0 bg-gray-950/50 backdrop-blur-sm dark:bg-gray-950/75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            x-show="show && showActiveComponent"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-bind:class="modalWidth"
            class="inline-block w-full align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform ring-1 ring-gray-950/5 transition-all dark:bg-gray-900 dark:ring-white/10 sm:my-8 sm:align-middle sm:w-full"
            id="modal-container"
            x-trap.noscroll.inert="show && showActiveComponent"
            aria-modal="true"
        >
            @forelse($components as $id => $component)
                <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                    @livewire($component['name'], $component['arguments'], key($id))
                </div>
            @empty
            @endforelse
        </div>
    </div>
</div>
