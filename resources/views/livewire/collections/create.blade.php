<div>
    <x-shopper::breadcrumb :back="route('shopper.collections.index')">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.collections.index')" :title="__('shopper::layout.sidebar.collections')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-3">
        <x-slot name="title">
            {{ __('shopper::messages.actions_label.add_new', ['name' => __('collection')]) }}
        </x-slot>

        <x-slot name="action">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </x-slot>
    </x-shopper::heading>

    <div class="mt-6 space-y-5 lg:space-y-0 lg:grid lg:grid-cols-6 lg:gap-6">
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 dark:bg-secondary-800">
                <x-shopper::forms.group :label="__('shopper::layout.forms.label.name')" for="name" isRequired :error="$errors->first('name')">
                    <x-shopper::forms.input wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Summers Collections, Christmas promotions...') }}" />
                </x-shopper::forms.group>
                <div class="mt-5">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.description')" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper::forms.group>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow overflow-hidden pt-4 sm:pt-5 dark:bg-secondary-800">
                <h3 class="text-base text-secondary-900 leading-6 px-4 sm:px-5 font-medium dark:text-white">
                    {{ __('shopper::pages/collections.filter_type') }}
                </h3>
                <div
                    x-data="{
                        active: {{ $type === 'auto' ? 1 : 0 }},
                        onArrowUp(index) {
                            this.select(this.active - 1 < 0 ? this.$refs.radiogroup.children.length - 1 : this.active - 1);
                        },
                        onArrowDown(index) {
                            this.select(this.active + 1 > this.$refs.radiogroup.children.length - 1 ? 0 : this.active + 1);
                        },
                        select(index) {
                            this.active = index;
                        },
                    }"
                    class="p-4 sm:p-5"
                >
                    <div class="bg-white rounded-md grid gap-4 sm:grid-cols-2 sm:gap-6 dark:bg-secondary-800" x-ref="radiogroup">

                        <div :class="{ 'border-secondary-200 dark:border-secondary-700': !(active === 0), 'bg-primary-50 border-primary-200 dark:bg-secondary-700 dark:border-secondary-700 z-10': active === 0 }" class="relative border rounded-md p-4 flex bg-primary-50 border-primary-200 dark:bg-secondary-700 dark:border-secondary-700 z-10">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.radio wire:model.debounce.500ms="type" id="collection-type-0" name="type" value="manual" @click="select(0)" @keydown.space="select(0)" @keydown.arrow-up="onArrowUp(0)" @keydown.arrow-down="onArrowDown(0)"/>
                            </div>
                            <label for="collection-type-0" class="ml-3 flex flex-col cursor-pointer">
                                <span :class="{ 'text-primary-900 dark:text-white': active === 0, 'text-secondary-900 dark:text-white': !(active === 0) }" class="block text-sm leading-5 font-medium text-primary-900 dark:text-white">
                                    {{ __('shopper::pages/collections.manual') }}
                                </span>
                                <span :class="{ 'text-primary-700': active === 0, 'text-secondary-500 dark:text-secondary-400': !(active === 0) }" class="mt-0.5 block text-xs leading-4 text-primary-700">
                                    {{ __('shopper::pages/collections.manual_description') }}
                                </span>
                            </label>
                        </div>

                        <div :class="{ 'border-secondary-200 dark:border-secondary-700': !(active === 1), 'bg-primary-50 border-primary-200 dark:bg-secondary-700 dark:border-secondary-700 z-10': active === 1 }" class="relative border rounded-md border-secondary-200 p-4 flex">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.radio wire:model.debounce.500ms="type" id="collection-type-1" name="type" value="auto" @click="select(1)" @keydown.space="select(1)" @keydown.arrow-up="onArrowUp(1)" @keydown.arrow-down="onArrowDown(1)"/>
                            </div>
                            <label for="collection-type-1" class="ml-3 flex flex-col cursor-pointer">
                                <span :class="{ 'text-primary-900 dark:text-white': active === 1, 'text-secondary-900 dark:text-white': !(active === 1) }" class="block text-sm leading-5 font-medium text-secondary-900">
                                    {{ __('shopper::pages/collections.automatic') }}
                                </span>
                                <span :class="{ 'text-primary-700': active === 1, 'text-secondary-500 dark:text-secondary-400': !(active === 1) }" class="mt-0.5 block text-xs leading-4 text-secondary-500">
                                    {{ __('shopper::pages/collections.automatic_description') }}
                                </span>
                            </label>
                        </div>

                    </div>
                </div>
                @if($type === 'auto')
                    <div class="border-t border-secondary-200 p-4 sm:p-5 space-y-5 dark:border-secondary-700">
                        <h3 class="text-base text-secondary-900 leading-6 font-medium dark:text-white">
                            {{ __('shopper::pages/collections.conditions.title') }}
                        </h3>
                        <div class="flex items-center space-x-6">
                            <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/collections.conditions.products_match') }}
                            </p>
                            <div class="flex items-center">
                                <x-shopper::forms.radio wire:model.lazy="condition_match" id="all" value="all" />
                                <label for="all" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-secondary-700 dark:text-secondary-300">
                                        {{ __('shopper::pages/collections.conditions.all') }}
                                    </span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <x-shopper::forms.radio wire:model.lazy="condition_match" id="any" value="any" />
                                <label for="any" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-secondary-700 dark:text-secondary-300">
                                        {{ __('shopper::pages/collections.conditions.any') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="space-y-4">
                                @foreach($conditions as $conditionKey => $conditionValue)
                                    <div wire:key="condition-{{ $conditionKey }}"  class="flex items-center space-x-4">
                                        <div class="grid grid-cols-3 gap-4">
                                            <div>
                                                <x-shopper::forms.select wire:model.defer="rule.{{ $conditionValue }}" aria-label="{{ __('shopper::pages/collections.conditions.rules') }}">
                                                    <option>{{ __('shopper::pages/collections.conditions.choose_rule') }}</option>
                                                    @foreach($this->collectionRules as $ruleKey => $ruleValue)
                                                        <option value="{{ $ruleKey }}" @if($loop->first) selected @endif>{{ $ruleValue['name'] }}</option>
                                                    @endforeach
                                                </x-shopper::forms.select>
                                                @error('rule.'.$conditionValue) <p class="mt-1 text-sm leading-5 text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <x-shopper::forms.select wire:model.defer="operator.{{ $conditionValue }}" aria-label="{{ __('shopper::pages/collections.conditions.title') }}">
                                                    <option>{{ __('shopper::pages/collections.conditions.select_operator') }}</option>
                                                    @foreach($this->operators as $operatorKey => $operatorValue)
                                                        <option value="{{ $operatorKey }}" @if($loop->first) selected @endif>{{ $operatorValue['name'] }}</option>
                                                    @endforeach
                                                </x-shopper::forms.select>
                                                @error('operator.'.$conditionValue) <p class="mt-1 text-sm leading-5 text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <div class="relative rounded-md shadow-sm">
                                                    <x-shopper::forms.input wire:model.defer="value.{{ $conditionValue }}" type="text" class="w-full pr-12" aria-label="{{ __('shopper::layout.forms.label.value') }}" placeholder="{{ __('shopper::layout.forms.placeholder.value') }}" />
                                                </div>
                                                @error('value.'.$conditionValue) <p class="mt-1 text-sm leading-5 text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <span class="inline-flex rounded-md shadow-sm">
                                                <x-shopper::buttons.default wire:click.prevent="remove({{ $conditionKey }})" type="button">
                                                    <x-heroicon-o-trash class="w-5 h-5" />
                                                </x-shopper::buttons.default>
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(count($conditions) < 4)
                                <div class="relative">
                                    <span class="inline-flex rounded-md shadow-sm">
                                        <x-shopper::buttons.default wire:click.prevent="add({{ $i }})" type="button">
                                            {{ count($conditions) === 0 ? __('shopper::pages/collections.conditions.add') : __('shopper::pages/collections.conditions.add_another') }}
                                        </x-shopper::buttons.default>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <x-shopper::forms.seo
                slug="collections"
                :title="$seoTitle"
                :url="str_slug($name)"
                :description="$seoDescription"
                :canUpdate="$updateSeo"
            />
        </div>
        <div class="lg:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div class="bg-white rounded-md shadow p-4 sm:p-5 dark:bg-secondary-800">
                    <x-datetime-picker
                        :label="__('shopper::layout.forms.label.availability')"
                        :placeholder="__('shopper::layout.forms.placeholder.pick_a_date')"
                        wire:model="publishedAt"
                        parse-format="YYYY-MM-DD HH:mm"
                        time-format="24"
                        without-timezone
                        class="dark:bg-secondary-700"
                    />
                    @if($publishedAt)
                        <div class="mt-2 flex items-start">
                            <div class="mt-1 shrink-0 w-2.5 h-2.5 rounded-full bg-primary-600"></div>
                            <p class="ml-2.5 text-sm text-secondary-500 leading-5 dark:text-secondary-400">
                                {{ __('shopper::messages.published_on') }} <br>
                                {{ $publishedAtFormatted }}
                            </p>
                        </div>
                    @else
                        <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/collections.availability_description') }}
                        </p>
                    @endif
                </div>
                <div class="bg-white rounded-md shadow overflow-hidden p-4 sm:p-5 dark:bg-secondary-800">
                    <h4 class="block text-sm font-medium leading-5 text-secondary-700 dark:text-secondary-300">
                        {{ __('shopper::layout.forms.label.image_preview') }}
                    </h4>
                    <div class="mt-1">
                        <livewire:shopper-forms.uploads.single />
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-secondary-200 pt-5 pb-10 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</div>
