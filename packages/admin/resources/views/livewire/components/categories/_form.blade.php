<div class="mt-8 space-y-5 lg:space-y-0 lg:grid lg:grid-cols-6 lg:gap-6">
    <div class="lg:col-span-4 space-y-5">
        <x-shopper::card class="p-4 sm:p-5">
            <div>
                <x-shopper::forms.group
                    :label="__('shopper::layout.forms.label.name')"
                    for="name"
                    isRequired
                    :error="$errors->first('name')"
                >
                    <x-shopper::forms.input
                        wire:model.defer="name"
                        id="name"
                        type="text"
                        autocomplete="off"
                        placeholder="Women Shoes, Baby Clothes clothes"
                    />
                </x-shopper::forms.group>
            </div>
            <div class="mt-4">
                <x-shopper::forms.group :label="__('shopper::layout.forms.label.parent')" for="category" wire:ignore>
                    <x-shopper::forms.select
                        wire:model.defer="selectedCategory"
                        id="category"
                        x-data="{}"
                        x-init="function () { choices($el) }"
                    >
                        <option value="0">{{ __('shopper::pages/categories.empty_parent') }}</option>
                        @foreach($categories as $cat)
                            @if($cat->id !== $categoryId)
                                <option value="{{ $cat->id }}" @selected($cat->id === $parent_id)>
                                    {{ $cat->name }}
                                </option>

                                @foreach($cat->children as $child)
                                    @includeWhen($child->id !== $categoryId, 'shopper::components.forms.option-category', [
                                        'name' => $cat->name,
                                        'category' => $child
                                    ])
                                @endforeach
                            @endif
                        @endforeach
                    </x-shopper::forms.select>
                </x-shopper::forms.group>
            </div>
            <div class="mt-5 py-4 border-t border-b border-gray-200 dark:border-gray-700">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5">
                        <span wire:model="is_enabled" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': !on, 'bg-primary-600': on }" class="bg-gray-200 relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-brand">
                            <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                            <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                        </span>
                    </div>
                    <div class="ml-3 text-sm leading-5">
                        <x-shopper::label for="online" :value="__('shopper::layout.forms.label.visibility')" />
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('shopper::words.set_visibility', ['name' => strtolower(__('shopper::words.category'))]) }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <x-shopper::forms.group :label="__('shopper::layout.forms.label.description')" for="description">
                    <livewire:shopper-forms.trix :value="$description" />
                </x-shopper::forms.group>
            </div>
        </x-shopper::card>

        <x-shopper::forms.seo
            slug="categories"
            :title="$seoTitle"
            :url="isset($category) ? $category->slug : str_slug($name)"
            :description="$seoDescription"
            :canUpdate="$updateSeo"
        />
    </div>
    <div class="lg:col-span-2">
        <aside class="sticky top-6 space-y-5">
            <x-shopper::card class="overflow-hidden">
                <div class="p-4 sm:p-5">
                    <x-shopper::label :value="__('shopper::layout.forms.label.image_preview')" />
                    <div class="mt-1">
                        <livewire:shopper-forms.uploads.single
                            :media="isset($category) ? $category->getFirstMedia(config('shopper.core.storage.collection_name')) : null"
                        />
                    </div>
                </div>
            </x-shopper::card>
        </aside>
    </div>
</div>
