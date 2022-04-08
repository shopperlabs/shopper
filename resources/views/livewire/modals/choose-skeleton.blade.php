<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        <span class="text-lg leading-6 font-medium text-secondary-900 capitalize dark:text-white">{{ $name }}</span>
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-3 sm:gap-6">
            @foreach($skeletons as $skeleton)
                <div class="relative rounded-md border border-secondary-300 bg-white px-3 py-2 shadow-sm flex items-center hover:border-secondary-400 dark:bg-secondary-700 dark:border-secondary-700 dark:hover:border-secondary-600">
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('shopper.settings.mails.create-template', ['type' => $type, 'name' => $name, 'skeleton' => $skeleton]) }}" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-secondary-900 capitalize dark:text-white">
                                {{ $skeleton }}
                            </p>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper::default-button wire:click="$emit('closeModal')" type="button">
                {{ __('Cancel') }}
            </x-shopper::default-button>
        </span>
    </x-slot>

</x-shopper::modal>
