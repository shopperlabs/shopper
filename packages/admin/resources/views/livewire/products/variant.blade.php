<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.products.index')">
        <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.products.edit', $product)"
            :title="$product->name"
            class="truncate"
        />
        <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
        <span class="text-secondary-500 dark:text-secondary-400 truncate">
            {{ $name }}
        </span>
    </x-shopper::breadcrumb>

    <div class="z-30 pb-5 mt-5 border-b border-secondary-200 dark:border-secondary-700">
        <div class="space-y-3 lg:flex lg:items-center lg:justify-between lg:space-y-0">
            <div class="flex items-center flex-1 min-w-0 space-x-3">
                <h3 class="text-2xl font-bold leading-6 text-secondary-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white font-display">
                    {{ $variant->name }}
                </h3>
                <span @class([
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    'bg-green-100 text-green-800' => $product->is_visible,
                    'bg-yellow-100 text-yellow-800' => ! $product->is_visible,
                ])>
                    {{ $product->is_visible ? __('shopper::layout.forms.label.visible'): __('shopper::layout.forms.label.invisible') }}
                </span>
            </div>
            <div class="hidden sm:block">
                <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.delete-product', {{ json_encode(['id' => $variant->id, 'type' => 'variant', 'route' => route('shopper.products.edit', $product)]) }})" type="button">
                    <x-heroicon-o-trash class="w-5 h-5 mr-2 -ml-1" />
                    {{ __('shopper::layout.forms.actions.delete') }}
                </x-shopper::buttons.danger>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">
                    {{ __('shopper::pages/products.variants.variant_information') }}
                </h3>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2">
                <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
                    <x-shopper::forms.group
                        for="name"
                        class="sm:col-span-4"
                        :label="__('shopper::layout.forms.label.name')"
                        :error="$errors->first('name')"
                        isRequired
                    >
                        <x-shopper::forms.input
                            wire:model.defer="name"
                            id="name"
                            type="text"
                            autocomplete="off"
                            class="dark:bg-secondary-800 dark:border-transparent"
                            placeholder="Black 128Go, primary 64Go..."
                        />
                    </x-shopper::forms.group>
                    <div class="sm:col-span-4">
                        <h4 class="block text-sm font-medium leading-5 text-secondary-700 dark:text-secondary-300">
                            {{ __('shopper::words.images') }}
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

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <h3 class="mt-2 text-lg font-semibold leading-6 text-secondary-900 dark:text-white font-display">
                    {{ __('shopper::words.pricing') }}
                </h3>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2">
                <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
                    <div class="sm:col-span-2">
                        <x-inputs.currency
                            :label="__('shopper::layout.forms.label.price_amount')"
                            placeholder="0.00"
                            wire:model.defer="price_amount"
                            class="dark:border-transparent dark:focus:border-primary-500 dark:text-white"
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
                            class="dark:border-transparent dark:focus:border-primary-500 dark:text-white"
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
                            class="dark:border-transparent dark:focus:border-primary-500 dark:text-white"
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

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <h3 class="mt-2 text-lg font-semibold leading-6 text-secondary-900 dark:text-white font-display">
                    {{ __('shopper::words.location') }}
                </h3>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2">
                <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="pb-5">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <x-shopper::forms.group
                                :label="__('shopper::layout.forms.label.sku')"
                                for="sku"
                                class="sm:col-span-1"
                                :error="$errors->first('sku')"
                            >
                                <x-shopper::forms.input wire:model.defer="sku" id="sku" class="dark:bg-secondary-800 dark:border-transparent" type="text" autocomplete="off" />
                            </x-shopper::forms.group>
                            <x-shopper::forms.group
                                :label="__('shopper::layout.forms.label.barcode')"
                                for="barcode"
                                class="sm:col-span-1"
                                :error="$errors->first('barcode')"
                            >
                                <x-shopper::forms.input wire:model.defer="barcode" id="barcode" class="dark:bg-secondary-800 dark:border-transparent" type="text" autocomplete="off" />
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
                                    class="dark:bg-secondary-800 dark:border-transparent"
                                    autocomplete="off"
                                />
                            </x-shopper::forms.group>
                        </div>
                    </div>
                    <div class="pt-5">
                        <div class="space-y-2 sm:space-y-0 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <h4 class="block text-sm font-medium leading-5 text-secondary-900 dark:text-white">
                                {{ __('shopper::pages/products.quantity_inventory') }}
                            </h4>
                            <div class="flex items-center sm:ml-4 space-x-3">
                                <button wire:click="$emit('openModal', 'shopper-modals.update-variant-stock', {{ json_encode([$variant->id]) }})" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-500 hover:text-primary-600">
                                    {{ __('shopper::pages/products.variants.actions.update_stock') }}
                                </button>
                                <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-500 hover:text-primary-600">
                                    {{ __('shopper::pages/products.variants.actions.manage_inventory') }}
                                </a>
                            </div>
                        </div>
                        <x-shopper::card class="mt-4 divide-y divide-secondary-200 dark:divide-secondary-700 overflow-hidden">
                            <div class="grid grid-cols-3 px-4 py-2 bg-secondary-50 dark:bg-secondary-700">
                                <div class="col-span-2">
                                    <span class="text-xs font-semibold leading-5 tracking-wider uppercase text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/products.inventory_name') }}
                                    </span>
                                </div>
                                <div class="flex justify-end col-span-1 pl-4 text-right">
                                    <span class="text-xs font-semibold leading-5 tracking-wider uppercase text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::words.available') }}
                                    </span>
                                </div>
                            </div>
                            @foreach($inventories as $inventory)
                                <div class="grid grid-cols-3 px-4 py-3" wire:key="inventory-{{ $inventory->id }}">
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
                        </x-shopper::card>
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
</x-shopper::container>
