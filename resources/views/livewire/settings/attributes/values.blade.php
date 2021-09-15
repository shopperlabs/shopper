<div class="pb-10">
    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ __('Attribute values') }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                {{ __('Add default values for this attribute. These values will be available on product attributes tabs.') }}
            </p>
        </div>
    </div>

    <section aria-labelledby="values_attributes_heading">
        <div class="mt-5 bg-white pt-5 space-y-5 shadow rounded-md overflow-hidden dark:bg-gray-800">
            <div class="px-4 sm:px-5 flex items-center justify-between space-x-4">
                <div>
                    <span class="shadow-sm rounded-md">
                        <x-shopper-button wire:click="$emit('openModal', 'shopper-modals.create-value', {{ json_encode([$attribute->id]) }})" type="button">
                            {{ __('Add new value') }}
                        </x-shopper-button>
                    </span>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden border-t border-gray-200 dark:border-gray-700">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th scope="col" class="pl-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400"></th>
                                        <th scope="col" class="pl-2 pr-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            {{ __('Value') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            {{ __('Key') }}
                                        </th>
                                        <th scope="col" class="relative px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                            <span class="sr-only">{{ __('Actions') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700" x-max="1">
                                    @forelse($values as $v)
                                        <tr>
                                            <td class="pl-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                                <x-shopper-input.checkbox aria-label="{{ __('Attribute value id') }}" id="is_filterable_{{ $v->id }}" :value="$v->id" />
                                            </td>
                                            <td class="pl-2 pr-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
                                                {{ $v->value }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
                                                {{ $v->key }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                                <div class="flex-1 flex justify-end items-center space-x-4">
                                                    <button wire:click="$emit('openModal', 'shopper-modals.update-value', {{ json_encode([$attribute->name, $type, $v->id]) }})" wire:key="{{ $v->id }}" type="button" class="text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-500">
                                                        <x-heroicon-o-pencil class="h-5 w-5" />
                                                    </button>
                                                    <button wire:click="removeValue({{ $v->id }})" type="button" class="text-red-600 hover:text-red-700 dark:text-red-500">
                                                        <x-heroicon-o-trash class="h-5 w-5" />
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    <span class="text-xl font-medium py-8 text-gray-500 dark:text-gray-400">{{ __('No values') }}...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
