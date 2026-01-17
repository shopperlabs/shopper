<div
    x-data="modal()"
    x-on:close.stop="setShowPropertyTo(false)"
    x-on:keydown.escape.window="closeModalOnEscape()"
    x-show="show"
    class="sh-modal fixed inset-0 z-50 overflow-y-auto"
    x-cloak
>
    <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-10 text-center sm:block sm:p-0">
        <div
            x-show="show"
            x-on:click="closeModalOnClickAway()"
            x-transition:enter="duration-300 ease-out"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="duration-200 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="sh-modal-backdrop fixed inset-0 bg-gray-950/50 dark:bg-gray-950/75"
        ></div>

        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>

        <div
            x-show="show && showActiveComponent"
            x-transition:enter="duration-300 ease-out"
            x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
            x-transition:leave="duration-200 ease-in"
            x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
            x-transition:leave-end="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
            x-bind:class="modalWidth"
            class="inline-block w-full transform overflow-hidden rounded-xl bg-white text-left align-bottom shadow-xl ring-1 ring-gray-200 transition-all sm:my-8 sm:w-full sm:align-middle dark:bg-gray-900 dark:ring-white/10"
            id="modal-container"
            x-trap.noscroll.inert="show && showActiveComponent"
            aria-modal="true"
        >
            @forelse ($components as $id => $component)
                <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                    @livewire($component['name'], $component['arguments'], key($id))
                </div>
            @empty

            @endforelse
        </div>
    </div>
</div>
