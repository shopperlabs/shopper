<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Livewire\Attributes\Async;
use Livewire\Attributes\Renderless;
use Shopper\Core\Models\Contracts\Category;
use Shopper\Traits\HandlesAuthorizationExceptions;

class ReOrderCategories extends SlideOverComponent
{
    use HandlesAuthorizationExceptions;

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    /**
     * @param  array<int, string>  $order
     */
    #[Renderless, Async]
    public function reorder(array $order, ?string $parentId = null): void
    {
        $this->authorize('categories.edit');

        $categoryModel = resolve(Category::class);

        $positions = array_flip(array_map(intval(...), $order));

        DB::transaction(function () use ($positions, $parentId, $categoryModel): void {
            foreach ($categoryModel::query()->whereKey(array_keys($positions))->get() as $category) {
                $category->fill([
                    'parent_id' => $parentId !== null ? (int) $parentId : null,
                    'position' => $positions[$category->getKey()] + 1,
                ]);

                $category->isDirty('parent_id') ? $category->save() : $category->saveQuietly();
            }
        });
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.re-order-categories', [
            'categories' => resolve(Category::class)::query()
                ->with('children.children.children')
                ->whereNull('parent_id')
                ->orderBy('position')
                ->get(),
        ]);
    }
}
