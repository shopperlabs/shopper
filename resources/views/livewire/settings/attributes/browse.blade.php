<div>

    <x:shopper-breadcrumb back="shopper.settings.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Settings') }}</a>
    </x:shopper-breadcrumb>
    <div class="mt-2 pb-5 border-b border-gray-200 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
        <h2 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ __('Attributes') }}</h2>
        @if($total > 0)
            @can('add_attributes')
                <div class="flex space-x-3">
                    <span class="shadow-sm rounded-md">
                        <a href="{{ route('shopper.settings.attributes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                            {{ __("Create") }}
                        </a>
                    </span>
                </div>
            @endcan
        @endif
    </div>

    @if($total === 0)
        <x-shopper-empty-state
            :title="__('Manage Attributes')"
            :content="__('Add custom attributes to your product to display for informations.')"
            :button="__('Add attribute')"
            permission="add_attributes"
            :url="route('shopper.settings.attributes.create')"
        >
            <div class="flex-shrink-0">
            </div>
        </x-shopper-empty-state>
    @else
        <div class="mt-6 bg-white shadow rounded-md">
            <div class="p-4 sm:p-6 sm:pb-4">
                <x-shopper-input.search
                    label="Search attributes"
                    placeholder="Search attribute by name"
                    wire:model.debounce.300ms="search"
                    wire:target="search"
                />
            </div>
            <div class="hidden sm:block">
                <div class="align-middle inline-block min-w-full">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-t border-gray-200">
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    <span class="lg:pl-2">{{ __("Name") }}</span>
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Type") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Is Searchable") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Is Filterable") }}
                                </th>
                                <th class="hidden md:table-cell px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Updated at") }}
                                </th>
                                <th class="pr-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100" x-max="1">
                            @forelse($attributes as $attribute)
                                <tr>
                                    <td class="px-6 py-3 max-w-sm whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        <div class="flex items-center space-x-3 lg:pl-2">
                                            <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $attribute->is_enabled ? 'bg-green-600': 'bg-gray-400' }}"></div>
                                            <div class="flex items-center">
                                                <a href="{{ route('shopper.settings.attributes.edit', $attribute) }}" class="ml-2 truncate hover:text-gray-600">
                                                    <span>{{ $attribute->name }} </span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3 table-cell text-sm leading-5 text-gray-500 font-medium">
                                        {{ $attribute->type_formatted }}
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-3 text-sm leading-5 text-gray-500 font-medium">
                                        {{ $attribute->is_searchable ? __('Yes') : __('No') }}
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-3 text-sm leading-5 text-gray-500 font-medium">
                                        {{ $attribute->is_filterable ? __('Yes') : __('No') }}
                                    </td>
                                    <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right">
                                        <time datetime="{{ $attribute->updated_at->format('Y-m-d') }}" class="capitalize">{{ $attribute->updated_at->formatLocalized('%d %B, %Y') }}</time>
                                    </td>
                                    <td class="pr-6">
                                        <div x-data="{ open: false }" x-on:item-removed.window="open = false" @keydown.escape="open = false" @click.away="open = false" class="relative flex justify-end items-center">
                                            <button id="project-options-menu-0" aria-has-popup="true" :aria-expanded="open" type="button" @click="open = !open" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                <svg class="w-5 h-5" x-description="Heroicon name: dots-vertical" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="mx-3 origin-top-right absolute right-7 top-0 w-48 mt-1 rounded-md shadow-lg" style="display: none;">
                                                <div class="relative z-10 rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="project-options-menu-0">
                                                    <div class="py-1">
                                                        <a href="{{ route('shopper.settings.attributes.edit', $attribute) }}" class="group flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" x-description="Heroicon name: pencil-alt" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ __("Edit") }}
                                                        </a>
                                                    </div>
                                                    <div class="border-t border-gray-100"></div>
                                                    <div class="py-1">
                                                        <button wire:click="remove({{ $attribute->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" x-description="Heroicon name: trash" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ __("Delete") }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                        <div class="flex justify-center items-center space-x-2">
                                            <svg class="h-8 w-8 text-cool-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-medium py-8 text-cool-gray-400 text-xl">{{ __("No attributes properties found") }}...</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white px-4 py-3 flex items-center rounded-b-md justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $attributes->links('shopper::livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700">
                            {{ __('Showing') }}
                            <span class="font-medium">{{ ($attributes->currentPage() - 1) * $attributes->perPage() + 1 }}</span>
                            {{ __('to') }}
                            <span class="font-medium">{{ ($attributes->currentPage() - 1) * $attributes->perPage() + count($attributes->items()) }}</span>
                            {{ __('of') }}
                            <span class="font-medium"> {!! $attributes->total() !!}</span>
                            {{ __('results') }}
                        </p>
                    </div>
                    {{ $attributes->links() }}
                </div>
            </div>
        </div>
    @endif

    <x-shopper-learn-more name="attributes" link="#" />

</div>
