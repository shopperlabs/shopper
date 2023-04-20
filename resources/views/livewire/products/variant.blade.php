<div>
    <x-shopper::breadcrumb :back="route('shopper.products.index')">
        <x-heroicon-s-chevron-left class="w-5 h-5 shrink-0 text-secondary-400" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.products.edit', $product)"
            :title="$product->name"
            class="max-w-xs w-full truncate"
        />
        <x-heroicon-s-chevron-left class="w-5 h-5 shrink-0 text-secondary-400" />
        <span class="text-secondary-500 dark:text-secondary-400 truncate">
            {{ $name }}
        </span>
    </x-shopper::breadcrumb>
    <div class="relative z-30 pb-5 mt-3 border-b bg-secondary-100 border-secondary-200 dark:bg-secondary-900 dark:border-secondary-700">
        <div class="space-y-4">
            <div class="space-y-3 md:flex md:items-start md:justify-between md:space-y-0">
                <div class="flex items-center flex-1 min-w-0 space-x-3">
                    <h3 class="text-2xl font-bold leading-6 text-secondary-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">
                        {{ $variant->name }}
                    </h3>
                    <span @class([
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                        'bg-green-100 text-green-800' => $product->is_visible,
                        'bg-yellow-100 text-yellow-800' => !$product->is_visible,
                    ])>
                        {{ $product->is_visible ? __('shopper::layout.forms.label.visible'): __('shopper::layout.forms.label.invisible') }}
                    </span>
                </div>
                <div class="flex pt-1 space-x-3">
                    <span class="hidden sm:block">
                        <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.delete-product', {{ json_encode(['id' => $variant->id, 'type' => 'variant', 'route' => route('shopper.products.edit', $product)]) }})" type="button">
                            <x-heroicon-o-trash class="w-5 h-5 mr-2 -ml-1" />
                            {{ __('shopper::layout.forms.actions.delete') }}
                        </x-shopper::buttons.danger>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">
                        {{ __('shopper::pages/products.variants.variant_information') }}
                    </h3>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="overflow-hidden rounded-md shadow">
                    <div class="px-4 py-5 bg-white sm:p-6 dark:bg-secondary-800">
                        <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
                            <x-shopper::forms.group
                                :label="__('shopper::layout.forms.label.name')"
                                for="name"
                                class="sm:col-span-4"
                                :error="$errors->first('name')"
                                isRequired
                            >
                                <x-shopper::forms.input
                                    wire:model.lazy="name"
                                    id="name"
                                    type="text"
                                    autocomplete="off"
                                    placeholder="{{ __('Black 128Go, primary 64Go...') }}"
                                />
                            </x-shopper::forms.group>
                            <div class="sm:col-span-4">
                                <h4 class="block text-sm font-medium leading-5 text-secondary-700 dark:text-secondary-300">
                                    {{ __('shopper::messages.images') }}
                                </h4>
                                <div class="mt-1">
                                    <x-shopper::forms.filepond
                                        wire:model="files"
                                        allowImagePreview
                                        multiple
                                        imagePreviewMaxHeight="200"
                                        allowFileTypeValidation
                                        allowFileSizeValidation
                                        maxFileSize="5mb"
                                        :images="$images"
                                    />
                                    @error('files.*')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="mt-2 text-lg font-semibold leading-6 text-secondary-900 dark:text-white">
                        {{ __('shopper::messages.pricing') }}
                    </h3>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="overflow-hidden rounded-md shadow">
                    <div class="px-4 py-5 bg-white sm:p-6 dark:bg-secondary-800">
                        <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
                            <div class="sm:col-span-2">
                                <x-inputs.currency
                                    :label="__('shopper::layout.forms.label.price_amount')"
                                    placeholder="0.00"
                                    wire:model.defer="price_amount"
                                    class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                    min="0"
                                    thousands=","
                                    decimal="."
                                    :suffix="$currency"
                                />
                            </div>
                            <div class="sm:col-span-2">
                                <x-inputs.currency
                                    :label="__('shopper::layout.forms.label.compare_price')"
                                    placeholder="0.00"
                                    wire:model.defer="old_price_amount"
                                    class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                    min="0"
                                    thousands=","
                                    decimal="."
                                    :suffix="$currency"
                                />
                            </div>
                            <div class="sm:col-span-2">
                                <x-inputs.currency
                                    :label="__('shopper::layout.forms.label.cost_per_item')"
                                    placeholder="0.00"
                                    wire:model.defer="cost_amount"
                                    class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                    min="0"
                                    thousands=","
                                    decimal="."
                                    :suffix="$currency"
                                    :hint="__('shopper::pages/products.cost_per_items_help_text')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="mt-2 text-lg font-semibold leading-6 text-secondary-900 dark:text-white">
                        {{ __('shopper::messages.inventory') }}
                    </h3>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="overflow-hidden bg-white rounded-md shadow dark:bg-secondary-800">
                    <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                <x-shopper::forms.group
                                    :label="__('shopper::layout.forms.label.sku')"
                                    for="sku"
                                    class="sm:col-span-1"
                                    :error="$errors->first('sku')"
                                >
                                    <x-shopper::forms.input wire:model.defer="sku" id="sku" type="text" autocomplete="off" />
                                </x-shopper::forms.group>
                                <x-shopper::forms.group
                                    :label="__('shopper::layout.forms.label.barcode')"
                                    for="barcode"
                                    class="sm:col-span-1"
                                    :error="$errors->first('barcode')"
                                >
                                    <x-shopper::forms.input wire:model.defer="barcode" id="barcode" type="text" autocomplete="off" />
                                </x-shopper::forms.group>
                            </div>
                            <div class="grid gap-4 pt-4 sm:grid-cols-2 sm:gap-6 sm:pt-5">
                                <x-shopper::forms.group
                                    :label="__('shopper::layout.forms.label.safety_stock')"
                                    :helpText="__('shopper::pages/products.safety_security_help_text')"
                                    for="security_stock"
                                    class="sm:col-span-1"
                                >
                                    <x-shopper::forms.input
                                        wire:model.defer="securityStock"
                                        id="security_stock"
                                        type="number"
                                        min="1"
                                        step="1"
                                        autocomplete="off"
                                    />
                                </x-shopper::forms.group>
                            </div>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center justify-between">
                                <h4 class="block text-sm font-medium leading-5 text-secondary-900 dark:text-white">
                                    {{ __('shopper::pages/products.quantity_inventory') }}
                                </h4>
                                <div class="flex items-center ml-4 space-x-3 sm:ml-0">
                                    <button wire:click="$emit('openModal', 'shopper-modals.update-variant-stock', {{ json_encode([$variant->id]) }})" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-600 hover:text-primary-800 dark:text-primary-500/50 dark:hover:text-primary-500">
                                        {{ __('shopper::pages/products.variants.actions.update_stock') }}
                                    </button>
                                    <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-600 hover:text-primary-800 dark:text-primary-500/50 dark:hover:text-primary-500">
                                        {{ __('shopper::pages/products.variants.actions.manage_inventory') }}
                                    </a>
                                </div>
                            </div>
                            <div class="mt-4 divide-y divide-secondary-200 dark:divide-secondary-700">
                                <div class="grid grid-cols-3 py-4">
                                    <div class="col-span-2">
                                        <span class="text-xs font-semibold leading-5 tracking-wider uppercase text-secondary-500 dark:text-secondary-400">
                                            {{ __('shopper::pages/products.inventory_name') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-end col-span-1 pl-4 text-right">
                                        <span class="text-xs font-semibold leading-5 tracking-wider uppercase text-secondary-500 dark:text-secondary-400">
                                            {{ __('shopper::messages.available') }}
                                        </span>
                                    </div>
                                </div>
                                @foreach($inventories as $inventory)
                                    <div class="grid grid-cols-3 py-4" wire:key="inventory-{{ $inventory->id }}">
                                        <div class="col-span-2">
                                            <span class="text-sm leading-5 text-secondary-700 dark:text-secondary-300">
                                                {{ $inventory->name }}
                                            </span>
                                        </div>
                                        <div class="flex justify-end col-span-1 pl-4 text-right">
                                            <span class="text-sm leading-5 text-secondary-700 dark:text-secondary-300">
                                                {{ $variant->stockInventory($inventory->id) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-5 mt-6 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" />
                {{ __('shopper::pages/products.variants.actions.update') }}
            </x-shopper::buttons.primary>
        </div>
    </div>

</div>
