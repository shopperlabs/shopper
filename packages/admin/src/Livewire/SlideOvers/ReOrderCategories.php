<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
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
    #[Renderless]
    public function reorder(array $order, ?string $parentId = null): void
    {
        $this->authorize('edit_categories');

        $categoryModel = resolve(Category::class);

        DB::transaction(function () use ($order, $parentId, $categoryModel): void {
            foreach ($order as $position => $categoryId) {
                $categoryModel::query()
                    ->where('id', (int) $categoryId)
                    ->update([
                        'parent_id' => $parentId !== null ? (int) $parentId : null,
                        'position' => $position + 1,
                    ]);
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
