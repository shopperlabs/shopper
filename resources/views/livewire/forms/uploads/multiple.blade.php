<div class="relative" x-data="{ focused: false }">
    <div
        x-ref="dragContainer"
        :class="focused ? 'bg-secondary-50 ' : 'border-secondary-300 dark:border-secondary-700 bg-transparent'"
        class="relative flex-1 w-full px-6 py-10 group flex justify-center border-2 border-secondary-300 border-dashed dark:border-secondary-700 hover:bg-secondary-50 dark:hover:bg-secondary-900 rounded-md cursor-pointer transition duration-150 ease-in-out">
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-20 w-20 text-secondary-400 group-hover:text-primary-500" xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 332.2 214" xml:space="preserve">
                <style>
                    .st0,
                    .st2{fill:none;stroke:#132144;stroke-width:7;stroke-linecap:round;stroke-linejoin:round}
                    .st2{fill:#fff}
                </style>
                <path class="st0" d="M213.4 23.3c-5.8-7.5-14.6-12.1-24.1-12.5C144.7 8.9 66.1 5.4 28.1 3.5 15.8 2.9 5.3 12.3 4.6 24.6c-2.8 53.9.6 116.1-.4 134.9-1 17.6 18.8 25.1 35 27.4"/>
                <path d="M223.2 22.7c12.2-.8 22.8 8.5 23.6 20.7 3.6 53.7 1.1 116 2.5 134.9 1.6 21.5-28.5 28.5-44.8 28.9-46.1 1.2-92.3.2-138.4-3-8.8-.6-19-2.1-23.5-9.7-2.3-3.8-2.5-8.4-2.7-12.8-1.2-24.3-5.4-79.1-8.2-114.7-1.3-17.8 12-33.2 29.8-34.5h.5c44.6-2.7 123.1-7.3 161.2-9.8z" fill="#377dff" stroke="#377dff" stroke-width="7" stroke-linecap="round" stroke-linejoin="round"/>
                <path class="st0" d="M39.2 167.9c29.1-12.8 39.4-33.3 56.6-39 17.1-5.7 31.7 22.8 38.5 33.4 4.5-12.4 9.3-25.1 17.9-35 8.7-9.9 22.6-16.4 35.3-12.9 8.2 2.3 14.8 8.3 21 14.2 11.8 11.1 26.7 24.5 40.4 33.1M124.6 54.5c5.2-3.4 15.5-5.3 26.7 6.9S153.1 95 130 90.5c-19.5-3.8-20.2-26.3-5.4-36z"/>
                <path class="st2" d="M222.6 171.5c-8.2-6.2-14.6-21.1-13.5-35.5 1-12.3 12.6-28.7 24.7-32.3 7.6-2.3 16.5-4.1 24.4-3.6 12 .7 19.5 7.6 25.2 17.8 8.7 15.7 9.2 34.5-3.1 48.6-8.5 9.6-17.6 12.9-30.3 14.3-6.1.6-19.1-3-27.4-9.3zM278.4 170.1c13.1 13.8 26.5 27.3 40.1 40.5M223.7 133.1c2.3-7.4 7.6-13.6 14.6-17M248.7 113.1l2.8-1M275.8 73.9l7.7-18M305.5 92.3l14.8-9.9M316.2 127.1l12.6-.1"/>
            </svg>
            <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                <span class="inline-flex items-center px-2.5 py-1.5 border border-secondary-300 shadow-sm text-xs font-medium rounded text-secondary-700 bg-white hover:bg-secondary-50 dark:bg-secondary-800 dark:border-secondary-700 dark:text-white focus:outline-none transition duration-150 ease-in-out">
                    {{ __('shopper::components.files.browse') }}
                </span>
                {{ __('shopper::components.files.drag_n_drop') }}
            </p>
            <p class="mt-1 text-xs text-secondary-500 dark:text-secondary-400">
                {{ __('shopper::components.files.type_size', ['size' => 5]) }}
            </p>
            <div class="mt-1" wire:loading.flex wire.target="files">
                <x-shopper::loader class="text-primary-600" />
                <span class="text-sm leading-5 text-secondary-400 dark:text-secondary-500">
                    {{ __('shopper::components.files.uploading') }}
                </span>
            </div>
        </div>
        <input
            class="absolute inset-0 z-50 w-full h-full outline-none opacity-0 cursor-pointer"
            type="file"
            accept="image/*"
            multiple
            id="{{ $inputId }}"
            wire:model="files"
            @focus="focused = true"
            @blur="focused = false"
            @dragover="$refs.dragContainer.classList.add('border-primary-200'); $refs.dragContainer.classList.add('ring-4'); $refs.dragContainer.classList.add('ring-inset');"
            @dragleave="$refs.dragContainer.classList.remove('border-primary-200'); $refs.dragContainer.classList.remove('ring-4'); $refs.dragContainer.classList.remove('ring-inset');"
            @drop="$refs.dragContainer.classList.remove('border-primary-200'); $refs.dragContainer.classList.remove('ring-4'); $refs.dragContainer.classList.remove('ring-inset');"
        />
    </div>
    @if(collect($files)->isNotEmpty())
        <ul class="mt-4 grid grid-cols-6 gap-4">
            @foreach($files as $file)
                <li class="relative group col-span-1 h-20 border border-secondary-300 dark:border-secondary-700 rounded-md overflow-hidden">
                    <img class="h-full w-full object-cover" src="{{ $file->temporaryUrl() }}" alt="" />
                    <div class="absolute h-full w-full flex items-center justify-center opacity-0 hover:opacity-100 focus-within:opacity-100 inset-0 bg-secondary-800 bg-opacity-75 transition duration-150 ease-in-out">
                        <button wire:click="removeUpload('files', '{{ $file->getFilename() }}')" type="button" class="inline-flex p-2 border-2 border-transparent text-white rounded-full hover:text-secondary-100 focus:outline-none focus:text-secondary-100 focus:bg-secondary-600 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    @error('files.*')
        <p class="mt-1 text-sm text-danger-500">{{ $message }}</p>
    @enderror
</div>
