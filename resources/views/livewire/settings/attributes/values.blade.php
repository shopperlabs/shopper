<div class="pb-10">
    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ __("Attribute values") }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                {{ __("Add default values for this attribute. These values will be available on product attributes tabs.") }}
            </p>
        </div>
    </div>

    <section aria-labelledby="values_attributes_heading">
        <div class="mt-5 bg-white pt-5 space-y-5 shadow rounded-md overflow-hidden">
            <div class="px-4 sm:px-5 flex items-center justify-between space-x-4">
                <div class="flex flex-1">
                    <label for="filter" class="sr-only">{{ __('Search attributes values') }}</label>
                    <div class="flex flex-grow rounded-md shadow-sm">
                        <div class="relative flex-grow focus-within:z-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                </svg>
                            </div>
                            <x-shopper-input.text id="filter" wire:model.debounce.350ms="search" class="rounded-none rounded-md pl-10" placeholder="{{ __('Search attribute value') }}" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <x-shopper-loader wire:loading wire:target="search" class="text-blue-600" />
                            </div>
                        </div>
                    </div>
                </div>
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
                        <div class="overflow-hidden border-t border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th scope="col" class="pl-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        </th>
                                        <th scope="col" class="pl-2 pr-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("Value") }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __("Key") }}
                                        </th>
                                        <th scope="col" class="relative px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            <span class="sr-only">{{ __('Actions') }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" x-max="1">
                                    @forelse($values as $v)
                                        <tr>
                                            <td class="pl-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                                <input aria-label="{{ __("Attribute value id") }}" id="is_filterable" type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 transition duration-150 ease-in-out" />
                                            </td>
                                            <td class="pl-2 pr-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                                {{ $v->value }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                                {{ $v->key }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                                <div class="flex-1 flex justify-end items-center space-x-4">
                                                    <button wire:click="$emit('openModal', 'shopper-modals.update-value', {{ json_encode([$attribute->name, $type, $v->id]) }})" wire:key="{{ $v->id }}" type="button" class="text-gray-500 hover:text-gray-600">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                        </svg>
                                                    </button>
                                                    <button wire:click="removeValue({{ $v->id }})" type="button" class="text-red-600 hover:text-red-900">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                                <div class="flex justify-center items-center space-x-2">
                                                    <svg class="h-8 w-8 text-cool-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    <span class="font-medium py-8 text-cool-gray-400 text-xl">{{ __('No values') }}...</span>
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
