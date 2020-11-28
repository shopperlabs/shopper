<div>

    <div class="mt-4 pb-5 border-b border-gray-200 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
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

    @endif

    <x-shopper-learn-more name="attributes" link="#" />

</div>
