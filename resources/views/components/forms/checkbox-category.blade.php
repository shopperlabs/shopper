<div class="relative flex items-start">
    <div class="flex items-center h-5">
        <x-shopper-forms.checkbox id="category-{{ $category->id }}" wire:model.defer="category_ids" value="{{ $category->id }}" />
    </div>
    <div class="ml-3 text-sm">
        <label for="category-{{ $category->id }}" class="font-medium text-secondary-700 dark:text-secondary-300">{{ $category->name }}</label>
    </div>
</div>

@if($category->children->isNotEmpty())
    <div class="ml-4 space-y-3">
        @foreach($category->children as $child)
            @include('shopper::components.forms.checkbox-category', ['parent' => $category->parent_id ,'category' => $child])
        @endforeach
    </div>
@endif
