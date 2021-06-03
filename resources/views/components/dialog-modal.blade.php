@props(['id' => null, 'maxWidth' => null])

@php
    $id = $id ?? md5($attributes->wire('model'));

    switch ($maxWidth ?? '2xl') {
        case 'sm':
          $maxWidth = 'sm:max-w-sm';
          break;
        case 'md':
          $maxWidth = 'sm:max-w-md';
          break;
        case 'lg':
          $maxWidth = 'sm:max-w-lg';
          break;
        case 'xl':
          $maxWidth = 'sm:max-w-xl';
          break;
        case '2xl':
        default:
          $maxWidth = 'sm:max-w-2xl';
          break;
    }
@endphp

<div x-data="{ show: @entangle($attributes->wire('model')) }" {{ $attributes }}
     id="{{ $id }}"
     x-show="show"
     x-on:close.stop="show = false"
     x-on:keydown.escape.window="show = false"
     class="fixed z-10 inset-0 overflow-y-auto"
     style="display: none;"
>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true"></span>

        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full {{ $maxWidth }} dark:bg-gray-800"
        >
            <div>
                <div class="sm:flex sm:items-start px-4 sm:px-6 pt-4">
                    <div class="text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            {{ $title }}
                        </h3>
                    </div>
                </div>
                <div class="p-4 sm:px-6">
                    {{ $content }}
                </div>
            </div>
            <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                {{ $footer }}
            </div>
        </div>
    </div>
</div>
