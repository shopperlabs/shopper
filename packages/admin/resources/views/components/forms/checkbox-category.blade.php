<div class="relative flex items-start">
    <div class="flex items-center h-5">
        <x-filament::input.checkbox
            id="category-{{ $category->id }}"
            wire:model="{{ $statePath }}"
            value="{{ $category->id }}"
            :valid="! $errors->has($statePath)"
        />
    </div>
    <div class="ml-3 text-sm">
        <x-shopper::label for="category-{{ $category->id }}" :value="$category->name" />
    </div>
</div>

@if($category->children->isNotEmpty())
    <div class="ml-6 space-y-3">
        @foreach($category->children as $child)
            @include('shopper::components.forms.checkbox-category', [
                'parent' => $category->parent_id,
                'category' => $child,
                'statePath' => $statePath,
            ])
        @endforeach
    </div>
@endif
